const express = require("express");
const { createProxyMiddleware } = require("http-proxy-middleware");
const app = express();

// ==========================
//  BUS SERVICE (port 8001)
// ==========================
app.use("/api/bus-service", createProxyMiddleware({
  target: "http://localhost:8001",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/bus-service": "" },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → bus-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (bus-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// ==========================
//  TICKET SERVICE (port 8002)
// ==========================
app.use("/api/ticket-service", createProxyMiddleware({
  target: "http://localhost:8003",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/ticket-service": "" },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → ticket-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (ticket-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// ==========================
//  PAYMENT SERVICE (port 8003)
// ==========================
app.use("/api/payment-service", createProxyMiddleware({
  target: "http://localhost:8002",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/payment-service": "" },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → payment-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (payment-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// // ==========================
// //  TRACKING SERVICE (port 8004)
// // ==========================
app.use("/api/tracking-service", createProxyMiddleware({
  target: "http://localhost:8004",
  changeOrigin: true,
  logLevel: "debug",
  pathRewrite: { "^/api/tracking-service": "" },
  onProxyReq(proxyReq, req, res) {
    console.log(`➡️ Forwarding ${req.method} ${req.originalUrl} → tracking-service`);
  },
  onError(err, req, res) {
    console.error("❌ Proxy Error (tracking-service):", err.message);
    res.status(500).send(err.message);
  }
}));

// ==========================
//  TEST ROUTE
// ==========================
app.get("/", (req, res) => res.send("Gateway up 🚀"));

// ==========================
//  START SERVER
// ==========================
app.listen(4000, () => console.log("Gateway running at http://localhost:4000"));
