const sqlite3 = require("sqlite3").verbose();
const path = require("path");

const DB_PATH = path.join(__dirname, "auth.db");

const db = new sqlite3.Database(DB_PATH, (err) => {
  if (err) {
    console.error("Database connection error:", err);
  } else {
    console.log("✓ Connected to SQLite database");
    initDatabase();
  }
});

// Initialize database schema
const initDatabase = () => {
  db.run(
    `CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      email TEXT UNIQUE NOT NULL,
      password TEXT NOT NULL,
      name TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )`
  );
};

// Get user by email
const getUserByEmail = (email) => {
  return new Promise((resolve, reject) => {
    db.get("SELECT * FROM users WHERE email = ?", [email], (err, row) => {
      if (err) reject(err);
      else resolve(row);
    });
  });
};

// Create user
const createUser = (email, hashedPassword, name) => {
  return new Promise((resolve, reject) => {
    db.run(
      "INSERT INTO users (email, password, name) VALUES (?, ?, ?)",
      [email, hashedPassword, name],
      function (err) {
        if (err) reject(err);
        else resolve({ id: this.lastID, email, name });
      }
    );
  });
};

// Get user by ID
const getUserById = (id) => {
  return new Promise((resolve, reject) => {
    db.get("SELECT id, email, name, created_at FROM users WHERE id = ?", [id], (err, row) => {
      if (err) reject(err);
      else resolve(row);
    });
  });
};

module.exports = {
  db,
  getUserByEmail,
  createUser,
  getUserById
};
