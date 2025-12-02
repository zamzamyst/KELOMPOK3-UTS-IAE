const jwt = require("jsonwebtoken");
const bcrypt = require("bcryptjs");
const { getUserByEmail, getUserById, createUser } = require("./database");

const SECRET_KEY = process.env.JWT_SECRET || "supersecretkey123";

// Middleware to verify JWT token
const verifyToken = (req, res, next) => {
  // Skip authentication for login, register and health check endpoints
  if (req.path === "/auth/login" || req.path === "/auth/register" || req.path === "/health") {
    return next();
  }

  const token = req.headers.authorization?.split(" ")[1]; // Bearer token

  if (!token) {
    return res.status(401).json({ error: "No token provided" });
  }

  try {
    const decoded = jwt.verify(token, SECRET_KEY);
    req.user = decoded; // Attach user info to request
    next();
  } catch (err) {
    return res.status(403).json({ error: "Invalid or expired token" });
  }
};

// Generate JWT token
const generateToken = (userId, email) => {
  return jwt.sign({ userId, email }, SECRET_KEY, {
    expiresIn: process.env.JWT_EXPIRY || "24h"
  });
};

// Hash password
const hashPassword = async (password) => {
  return await bcrypt.hash(password, 10);
};

// Compare password
const comparePassword = async (password, hash) => {
  return await bcrypt.compare(password, hash);
};

// Login handler
const login = async (email, password) => {
  const user = await getUserByEmail(email);

  if (!user) {
    throw new Error("User not found");
  }

  const validPassword = await comparePassword(password, user.password);

  if (!validPassword) {
    throw new Error("Invalid password");
  }

  const token = generateToken(user.id, user.email);
  return {
    token,
    user: {
      id: user.id,
      email: user.email,
      name: user.name
    }
  };
};

// Register handler
const register = async (email, password, name) => {
  const existingUser = await getUserByEmail(email);

  if (existingUser) {
    throw new Error("Email already registered");
  }

  const hashedPassword = await hashPassword(password);
  const user = await createUser(email, hashedPassword, name);

  const token = generateToken(user.id, user.email);
  return {
    token,
    user: {
      id: user.id,
      email: user.email,
      name: user.name
    }
  };
};

module.exports = { verifyToken, generateToken, login, register };
