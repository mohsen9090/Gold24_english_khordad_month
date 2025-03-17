require("dotenv").config();  // بارگذاری متغیرهای محیطی از فایل .env
const express = require("express");
const mysql = require("mysql2");
const nodemailer = require("nodemailer");
const bcrypt = require("bcryptjs");
const crypto = require("crypto");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();
const PORT = process.env.PORT || 3000;  // پورت پیشفرض یا پورت محیطی

// Middleware setup
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Database connection
const db = mysql.createConnection({
    host: process.env.DB_HOST || "localhost",  // اگر متغیر محیطی موجود نباشد localhost استفاده می‌شود
    user: process.env.DB_USER || "gold24_user",  // یوزر از .env گرفته می‌شود
    password: process.env.DB_PASS || "random_password",  // پسورد از .env گرفته می‌شود
    database: process.env.DB_NAME || "gold24_db"  // نام دیتابیس از .env
});

// Connect to database
db.connect((err) => {
    if (err) {
        console.error("Database connection error:", err);
        return;
    }
    console.log("Database connected successfully!");
});

// Email configuration
const transporter = nodemailer.createTransport({
    service: "gmail",
    auth: {
        user: process.env.EMAIL_USER || "mohsenbanihashemi4@gmail.com",  // ایمیل از .env
        pass: process.env.EMAIL_PASS || "vtjc wfmg blae qwuw"  // پسورد ایمیل از .env
    }
});

// Password reset endpoint
app.post("/forgot-password", async (req, res) => {
    const { email } = req.body;

    if (!email) {
        return res.status(400).json({ message: "Please enter your email!" });
    }

    try {
        const [results] = await db.promise().query("SELECT * FROM users WHERE email = ?", [email]);

        if (results.length === 0) {
            return res.status(404).json({ message: "Email not found!" });
        }

        const newPassword = crypto.randomInt(100000, 999999).toString();  // پسورد جدید
        const hashedPassword = await bcrypt.hash(newPassword, 10);  // هش کردن پسورد جدید

        await db.promise().query("UPDATE users SET password = ? WHERE email = ?", [hashedPassword, email]);

        const mailOptions = {
            from: process.env.EMAIL_USER,
            to: email,
            subject: "Password Reset - Gold24",
            html: `
                <div style="font-family: Arial, sans-serif; padding: 20px;">
                    <h2>Password Reset</h2>
                    <p>Your new password: <strong>${newPassword}</strong></p>
                    <p>Please change your password after logging in.</p>
                </div>
            `
        };

        await transporter.sendMail(mailOptions);  // ارسال ایمیل
        res.json({ message: "New password sent to email!" });

    } catch (error) {
        console.error("Error:", error);
        res.status(500).json({ message: "Server error!" });
    }
});

// Homepage route
app.get("/", (req, res) => {
    res.send(`
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Reset</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    padding: 50px;
                    background-color: #f5f5f5;
                }
                .container {
                    max-width: 400px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                input {
                    width: 100%;
                    max-width: 300px;
                    padding: 10px;
                    margin: 10px 0;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                }
                button {
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 16px;
                }
                button:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Password Reset</h2>
                <form action="/forgot-password" method="post">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <br>
                    <button type="submit">Send New Password</button>
                </form>
            </div>
        </body>
        </html>
    `);
});

// Start server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
