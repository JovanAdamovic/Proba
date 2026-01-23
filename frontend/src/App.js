import { Routes, Route } from "react-router-dom";
import { AuthProvider } from "./auth/AuthContext";
import ProtectedRoute from "./auth/ProtectedRoute";
import Navbar from "./components/Navbar";

import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import Predmeti from "./pages/Predmeti";
import Zadaci from "./pages/Zadaci";
import Predaje from "./pages/Predaje";

function App() {
  return (
    <AuthProvider>
      <Navbar />
      <Routes>
        <Route path="/login" element={<Login />} />

        <Route path="/" element={<ProtectedRoute><Dashboard /></ProtectedRoute>} />
        <Route path="/predmeti" element={<ProtectedRoute><Predmeti /></ProtectedRoute>} />
        <Route path="/zadaci" element={<ProtectedRoute><Zadaci /></ProtectedRoute>} />
        <Route path="/predaje" element={<ProtectedRoute><Predaje /></ProtectedRoute>} />
      </Routes>
    </AuthProvider>
  );
}

export default App;
