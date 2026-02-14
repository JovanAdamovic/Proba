import { Link, useNavigate, useLocation } from "react-router-dom";
import { useAuth } from "../auth/AuthContext";

export default function Navbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  if (location.pathname === "/login") return null;

  async function handleLogout() {
    await logout();
    navigate("/login");
  }

  const isAdmin = user?.uloga === "ADMIN";

  return (
    <div
      style={{
        padding: 12,
        borderBottom: "1px solid #ddd",
        display: "flex",
        gap: 12,
        alignItems: "center",
      }}
    >
      <Link to="/">Početna</Link>

      {user && (
        <>
          <Link to="/predmeti">
            {isAdmin ? "Predmeti" : "Moji predmeti"}
          </Link>

          <Link to="/zadaci">
            {isAdmin ? "Zadaci" : "Moji zadaci"}
          </Link>

          <Link to="/predaje">
            {isAdmin ? "Predaje" : "Moje predaje"}
          </Link>

          <div
            style={{
              marginLeft: "auto",
              display: "flex",
              gap: 12,
              alignItems: "center",
            }}
          >
            <span>
              {user.ime} ({user.uloga})
            </span>

            <button onClick={handleLogout}>Logout</button>
          </div>
        </>
      )}
    </div>
  );
}
