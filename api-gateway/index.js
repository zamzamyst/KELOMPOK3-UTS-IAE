require("dotenv").config();
const express = require("express");
const { createProxyMiddleware } = require("http-proxy-middleware");
const { verifyToken, login, register } = require("./auth");
const app = express();

app.use(express.json());

// ==========================
//  AUTH ROUTES
// ==========================
app.post("/auth/register", async (req, res) => {
  try {
    const { email, password, name } = req.body;

    if (!email || !password || !name) {
      return res.status(400).json({ error: "Email, password, and name required" });
    }

    const result = await register(email, password, name);
    res.status(201).json(result);
  } catch (err) {
    res.status(400).json({ error: err.message });
  }
});

app.post("/auth/login", async (req, res) => {
  try {
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({ error: "Email and password required" });
    }

    const result = await login(email, password);
    res.json(result);
  } catch (err) {
    res.status(401).json({ error: err.message });
  }
});

app.get("/health", (req, res) => {
  res.json({ status: "Gateway is running ✓" });
});

// Apply JWT verification to all API routes
app.use("/api", verifyToken);

// ==========================
//  BUS SERVICE (port 8001)
// ==========================
app.use("/api/bus-service", createProxyMiddleware({
  target: "http://localhost:8001",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/bus-service": '' },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → bus-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (bus-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// also proxy the web UI root so dashboard can open service web UI via gateway
app.use("/bus-service", createProxyMiddleware({
  target: "http://localhost:8001",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/bus-service": "" },
  onProxyReq(proxyReq, req, res) { console.log(`➡️ Forwarding UI ${req.method} ${req.originalUrl} → bus-service`); },
  onProxyRes(proxyRes, req, res) {
    if (proxyRes.headers && proxyRes.headers.location) {
      let loc = String(proxyRes.headers.location);
      // absolute URL pointing to service host -> rewrite to gateway + prefix
      if (loc.startsWith('http://localhost:8001') || loc.startsWith('http://127.0.0.1:8001')) {
        proxyRes.headers.location = loc.replace(/https?:\/\/localhost:8001|https?:\/\/127.0.0.1:8001/, 'http://localhost:4000/bus-service');
      }
      // relative location like /buses -> prefix with gateway + service
      else if (loc.startsWith('/')) {
        proxyRes.headers.location = 'http://localhost:4000/bus-service' + loc;
      }
    }
  },
  onError(err, req, res) { console.error("❌ Proxy Error (bus-service-ui):", err.message); res.status(500).send(err.message); }
}));

// ==========================
//  TICKET SERVICE (port 8002)
// ==========================
app.use("/api/ticket-service", createProxyMiddleware({
  target: "http://localhost:8003",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/ticket-service": '' },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → ticket-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (ticket-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// UI proxy for ticket-service
app.use("/ticket-service", createProxyMiddleware({
  target: "http://localhost:8003",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/ticket-service": "" },
  onProxyReq(proxyReq, req, res) { console.log(`➡️ Forwarding UI ${req.method} ${req.originalUrl} → ticket-service`); },
  onProxyRes(proxyRes, req, res) {
    if (proxyRes.headers && proxyRes.headers.location) {
      let loc = String(proxyRes.headers.location);
      if (loc.startsWith('http://localhost:8003') || loc.startsWith('http://127.0.0.1:8003')) {
        proxyRes.headers.location = loc.replace(/https?:\/\/localhost:8003|https?:\/\/127.0.0.1:8003/, 'http://localhost:4000/ticket-service');
      } else if (loc.startsWith('/')) {
        proxyRes.headers.location = 'http://localhost:4000/ticket-service' + loc;
      }
    }
  },
  onError(err, req, res) { console.error("❌ Proxy Error (ticket-service-ui):", err.message); res.status(500).send(err.message); }
}));

// ==========================
//  PAYMENT SERVICE (port 8003)
// ==========================
app.use("/api/payment-service", createProxyMiddleware({
  target: "http://localhost:8002",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/payment-service": '' },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → payment-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (payment-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// UI proxy for payment-service
app.use("/payment-service", createProxyMiddleware({
  target: "http://localhost:8002",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/payment-service": "" },
  onProxyReq(proxyReq, req, res) { console.log(`➡️ Forwarding UI ${req.method} ${req.originalUrl} → payment-service`); },
  onProxyRes(proxyRes, req, res) {
    if (proxyRes.headers && proxyRes.headers.location) {
      let loc = String(proxyRes.headers.location);
      if (loc.startsWith('http://localhost:8002') || loc.startsWith('http://127.0.0.1:8002')) {
        proxyRes.headers.location = loc.replace(/https?:\/\/localhost:8002|https?:\/\/127.0.0.1:8002/, 'http://localhost:4000/payment-service');
      } else if (loc.startsWith('/')) {
        proxyRes.headers.location = 'http://localhost:4000/payment-service' + loc;
      }
    }
  },
  onError(err, req, res) { console.error("❌ Proxy Error (payment-service-ui):", err.message); res.status(500).send(err.message); }
}));

// // ==========================
// //  TRACKING SERVICE (port 8004)
// // ==========================
app.use("/api/tracking-service", createProxyMiddleware({
  target: "http://localhost:8004",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/tracking-service": '' },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → tracking-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (tracking-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// UI proxy for tracking-service
app.use("/tracking-service", createProxyMiddleware({
  target: "http://localhost:8004",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/tracking-service": "" },
  onProxyReq(proxyReq, req, res) { console.log(`➡️ Forwarding UI ${req.method} ${req.originalUrl} → tracking-service`); },
  onProxyRes(proxyRes, req, res) {
    if (proxyRes.headers && proxyRes.headers.location) {
      let loc = String(proxyRes.headers.location);
      if (loc.startsWith('http://localhost:8004') || loc.startsWith('http://127.0.0.1:8004')) {
        proxyRes.headers.location = loc.replace(/https?:\/\/localhost:8004|https?:\/\/127.0.0.1:8004/, 'http://localhost:4000/tracking-service');
      } else if (loc.startsWith('/')) {
        proxyRes.headers.location = 'http://localhost:4000/tracking-service' + loc;
      }
    }
  },
  onError(err, req, res) { console.error("❌ Proxy Error (tracking-service-ui):", err.message); res.status(500).send(err.message); }
}));

// ==========================
//  TEST ROUTE
// ==========================
app.get("/", (req, res) => res.send("Gateway up 🚀"));

// ==========================
//  START SERVER
// ==========================
app.listen(4000, () => console.log("Gateway running at http://localhost:4000"));
