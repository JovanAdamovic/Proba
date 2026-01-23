import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../auth/AuthContext";

export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  async function handleLogout() {
    await logout();
    navigate("/login");
  }

  return (
    <div style={{ padding: 12, borderBottom: "1px solid #ddd", display: "flex", gap: 12 }}>
      <Link to="/">Pocetna</Link>

      {user ? (
        <>
          <Link to="/dashboard">Dashboard</Link>
          <Link to="/predmeti">Predmeti</Link>
          <Link to="/zadaci">Zadaci</Link>
          <Link to="/predaje">Predaje</Link>

          {/* profesor-only */}
          {user.uloga === "PROFESOR" && <Link to="/provere">Provere plagijata</Link>}

          <div style={{ marginLeft: "auto", display: "flex", gap: 12, alignItems: "center" }}>
            <span>{user.ime} ({user.uloga})</span>
            <button onClick={handleLogout}>Logout</button>
          </div>
        </>
      ) : (
        <div style={{ marginLeft: "auto" }}>
          <Link to="/login">Login</Link>
        </div>
      )}
    </div>
  );
}
