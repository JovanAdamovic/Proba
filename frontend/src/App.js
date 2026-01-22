import './App.css';
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

         
        </Routes>
       
      </BrowserRouter>
    </div>
  );
}

export default App;
