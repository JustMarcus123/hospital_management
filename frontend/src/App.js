import "./App.css";
import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import { ListUser } from "./components/ListUser";
import CreateUser from "./components/CreateUser";

function App() {
  return (
    <div className="App">
      Testing react connectivity with php and creating API'S
      <BrowserRouter>
        <nav className="navbar">
          <div className="navbar-container">
            <h1 className="navbar-logo">App Name</h1>
            <ul className="navbar-menu">
              <li className="navbar-item">
                <Link to="/" className="navbar-link">
                  Users List
                </Link>
              </li>
              <li className="navbar-item">
                <Link to="user/create" className="navbar-link">
                  Create User
                </Link>
              </li>
            </ul>
          </div>
        </nav>
        <Routes>
          <Route index path="/" element={<ListUser />} />
          <Route path="user/create" element={<CreateUser />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
