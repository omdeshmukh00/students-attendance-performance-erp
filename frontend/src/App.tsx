import { Routes, Route, Navigate } from "react-router-dom";
import AdminLogin from "./pages/AdminLogin";
import AdminDashboard from "./pages/AdminDashboard";
import ProtectedRoute from "./components/ProtectedRoute";
import StudentLogin from "./pages/LoginPage";
import StudentDashboard from "./pages/DashboardPage";

function App() {
  return (
    <Routes>

      {/* Default Route */}
      <Route path="/" element={<StudentLogin />} />

      {/* Student Routes */}
      <Route path="/dashboard/:bt_id" element={<StudentDashboard />} />

      {/* Admin Routes */}
      <Route path="/admin/login" element={<AdminLogin />} />

      <Route
        path="/admin/dashboard"
        element={
          <ProtectedRoute>
            <AdminDashboard />
          </ProtectedRoute>
        }
      />


    </Routes>
  );
}

export default App;