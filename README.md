
---

## 🛠️ Setup Instructions

1. Clone this repository or download ZIP
2. Copy project to your XAMPP `htdocs` folder
3. Start **Apache** and **MySQL** from XAMPP
4. Open **phpMyAdmin** and import:  
   `db/digital_store.sql` into a new database called `digital_store`
5. Edit `includes/db.php` to set your DB credentials
6. Open browser and go to:  
   `http://localhost/store/`

✅ Done! You're ready to test and run your store.

---

## 💳 Payment Flow (Manual Verification)

1. User selects a product and goes to **checkout**
2. Shows your UPI ID (e.g., `codesaif@upi`)
3. User uploads payment screenshot
4. Admin verifies payment from dashboard
5. Once verified, user receives access/download option

---

## 🚀 Technologies Used

- PHP 8+
- MySQL / MariaDB
- HTML5, CSS3, Bootstrap
- Basic session-based auth
- Manual UPI verification flow

---

## 📌 To-Do & Future Features

- [ ] Automatic UPI verification (via Razorpay API or custom logic)
- [ ] Product ratings and reviews
- [ ] Email notification system
- [ ] Coupon codes or discounts
- [ ] Search & filter products
- [ ] Download tracking dashboard

---

## 🤝 Developed By

**CodeSaif**  
🔗 [Website](https://codesaif.in) | 📬 [contact.codesaif@gmail.com](mailto:contact.codesaif@gmail.com)  
📱 Telegram: [@codesaif_group](https://t.me/codesaif_group)  
📸 Instagram: [@mdsaifali11](https://instagram.com/mdsaifali11)

---

## 📜 License

This project is open source and available under the [MIT License](LICENSE).
