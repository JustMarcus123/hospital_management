import React, { useState } from "react";
import axios from "axios";

const CreateUser = () => {
  const [form, setForm] = useState({
    id: "",
    name: "",
    email: "",
    password: "",
    role: "",
  });

  //handling form input changes:

  const HandleChange = (e) => {
    const { name, value } = e.target;
    setForm({ ...form, [name]: value });
  };

  //handle form submission

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.post(
        "http://localhost/hospitalmanagement/hospital_management/backend/api.php",
        form
      );
      console.log("Response:", response.data);
    } catch (error) {
      console.log("error:", error);
    }
  };

  return (
    <>
      <div>Create User</div>
      <form onSubmit={handleSubmit}>
        {/* Hidden input to hold the user ID */}
        <input
          type="hidden"
          name="id"
          value={form.id}
          onChange={HandleChange}
          required
        />

        {/* Name */}
        <label htmlFor="name">Name:</label>
        <input
          type="text"
          id="name"
          name="name"
          value={form.name}
          onChange={HandleChange}
          maxLength="255"
          required
        />
        <br />
        <br />

        {/* Email */}
        <label htmlFor="email">Email:</label>
        <input
          type="email"
          id="email"
          name="email"
          value={form.email}
          onChange={HandleChange}
          maxLength="255"
          required
        />
        <br />
        <br />

        {/* Password */}
        <label htmlFor="password">Password:</label>
        <input
          type="password"
          id="password"
          name="password"
          value={form.password}
          onChange={HandleChange}
          maxLength="255"
          required
        />
        <br />
        <br />

        {/* Role */}
        <label htmlFor="role">Role:</label>
        <select
          id="role"
          name="role"
          value={form.role}
          onChange={HandleChange}
          required
        >
          <option value="doctor">Doctor</option>
          <option value="nurse">Nurse</option>
          <option value="admin">Admin</option>
          <option value="staff">Staff</option>
        </select>
        <br />
        <br />

        {/* Submit Button */}
        <button type="submit">Update</button>
      </form>
    </>
  );
};

export default CreateUser;
