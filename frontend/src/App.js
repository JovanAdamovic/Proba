import { Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider } from "./auth/AuthContext";
import ProtectedRoute from "./auth/ProtectedRoute";

import Layout from "./components/Layout";

import Login from "./pages/Login";
import Pocetna from "./pages/Pocetna";
import Predmeti from "./pages/Predmeti";
import Zadaci from "./pages/Zadaci";
import Predaje from "./pages/Predaje";

function App() {
  return (
    <AuthProvider>
      <Routes>

        {/* LOGIN — BEZ NAVBARA */}
        <Route path="/login" element={<Login />} />

        {/* SVE ISPOD OVOGA IMA NAVBAR */}
        <Route
          path="/"
          element={
            <ProtectedRoute>
              <Layout>
                <Pocetna />
              </Layout>
            </ProtectedRoute>
          }
        />

        <Route
          path="/predmeti"
          element={
            <ProtectedRoute>
              <Layout>
                <Predmeti />
              </Layout>
            </ProtectedRoute>
          }
        />

        <Route
          path="/zadaci"
          element={
            <ProtectedRoute>
              <Layout>
                <Zadaci />
              </Layout>
            </ProtectedRoute>
          }
        />

        <Route
          path="/predaje"
          element={
            <ProtectedRoute>
              <Layout>
                <Predaje />
              </Layout>
            </ProtectedRoute>
          }
        />

        {/* fallback */}
        <Route path="*" element={<Navigate to="/" />} />
      </Routes>
    </AuthProvider>
  );
}

export default App;
