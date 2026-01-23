import { Routes, Route, Navigate } from "react-router-dom";
import ProtectedRoute from "./auth/ProtectedRoute";
import Navbar from "./components/Navbar";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import Predmeti from "./pages/Predmeti";
import Zadaci from "./pages/Zadaci";
import Predaje from "./pages/Predaje";

export default function App() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />

      <Route
        path="/*"
        element={
          <ProtectedRoute>
            <div>
              <Navbar />
              <Routes>
                <Route path="dashboard" element={<Dashboard />} />
                <Route path="predmeti" element={<Predmeti />} />
                <Route path="zadaci" element={<Zadaci />} />
                <Route path="predaje" element={<Predaje />} />
                <Route path="*" element={<Navigate to="/dashboard" replace />} />
              </Routes>
            </div>
          </ProtectedRoute>
        }
      />
    </Routes>
  );
}

























{/*import './App.css';
import Navbar from './components/Navbar';
import LoginPage from './pages/LoginPage';
import Pocetna from './pages/Pocetna';
import { BrowserRouter, Route, Routes } from 'react-router-dom';


function App() {
  return (
    <div className="App">
      <BrowserRouter>
      <Navbar></Navbar>
        <Routes>
          
            <Route path="/" element={<Pocetna />} />
             <Route path="/login" element={<LoginPage />} />
             {/* <Route path="/register" element={<Register />} />*/}

         
        //</Routes>
       
     // </BrowserRouter>
   // </div>
 /// );
//}

//export default App;
//*/}