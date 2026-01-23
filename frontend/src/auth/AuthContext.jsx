import { createContext, useContext, useEffect, useState } from "react";

const AuthContext = createContext(null);

const API_URL = "http://127.0.0.1:8000/api";

export function AuthProvider({ children }) {
  const [token, setToken] = useState(() => localStorage.getItem("token") || "");
  const [user, setUser] = useState(() => {
    const raw = localStorage.getItem("user");
    return raw ? JSON.parse(raw) : null;
  });

  // helper za auth header
  const authHeaders = () =>
    token ? { Authorization: `Bearer ${token}` } : {};

  async function login(email, password) {
    const res = await fetch(`${API_URL}/login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password }),
    });

    const data = await res.json();
    if (!res.ok) throw new Error(data?.message || "Login error");

    localStorage.setItem("token", data.access_token);
    localStorage.setItem("user", JSON.stringify(data.user));
    setToken(data.access_token);
    setUser(data.user);
  }

  async function logout() {
    try {
      // nije problem i ako failuje (token istekao) — svakako cistimo local state
      await fetch(`${API_URL}/logout`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          ...authHeaders(),
        },
      });
    } finally {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      setToken("");
      setUser(null);
    }
  }

  // (opciono) osvezi user preko /me kad postoji token
  useEffect(() => {
    if (!token) return;
    fetch(`${API_URL}/me`, { headers: authHeaders() })
      .then((r) => (r.ok ? r.json() : null))
      .then((me) => {
        if (me) {
          localStorage.setItem("user", JSON.stringify(me));
          setUser(me);
        }
      })
      .catch(() => {});
  }, [token]);

  return (
    <AuthContext.Provider value={{ token, user, login, logout, authHeaders }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
