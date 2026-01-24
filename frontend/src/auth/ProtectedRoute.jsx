import { Navigate } from "react-router-dom";
import { useAuth } from "../auth/AuthContext";

export default function ProtectedRoute({ children, allow }) {
  const { token, user } = useAuth();

  if (!token) return <Navigate to="/login" replace />;

  // allow = ["PROFESOR"] npr.
  if (allow && user && !allow.includes(user.uloga)) {
    return <Navigate to="/" replace />;
  }

  return children;
}
