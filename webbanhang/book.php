/* styles.css */

/* Import font Google */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Inter', sans-serif;
  background: #f7f9fb;
  color: #333;
  line-height: 1.5;
}

a { text-decoration: none; color: #0077cc; }
a:hover { color: #005fa3; }

.container {
  max-width: 1100px;
  margin: auto;
  padding: 16px;
}

/* HEADER */
header {
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  margin-bottom: 20px;
}

header .brand {
  font-size: 1.4rem;
  font-weight: 600;
  color: #0077cc;
}

header nav {
  display: flex;
  align-items: center;
  gap: 16px;
}

header .search input {
  padding: 6px 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

header .search button {
  padding: 6px 12px;
  border: none;
  background: #0077cc;
  color: white;
  border-radius: 8px;
  cursor: pointer;
}

header .search button:hover {
  background: #005fa3;
}

/* FOOTER */
.foot {
  text-align: center;
  color: #777;
  margin-top: 40px;
  padding: 16px;
  border-top: 1px solid #ddd;
}

/* GRID SÁCH */
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
  gap: 20px;
}

.card {
  background: #fff;
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  transition: transform .2s;
}

.card:hover { transform: translateY(-4px); }

.card img {
  max-width: 100%;
  height: 260px;
  object-fit: cover;
  border-radius: 8px;
}

.card h3 { font-size: 1.1rem; margin: 10px 0 4px; }
.card .price { font-weight: 600; color: #d32f2f; margin: 6px 0; }

.btn {
  background: #0077cc;
  color: #fff;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.2s;
}
.btn:hover { background: #005fa3; }
.btn:disabled { background: #999; cursor: not-allowed; }

/* FORM */
.form {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 20px;
}
.form input, .form textarea, .form select {
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 1rem;
  width: 100%;
}
.form textarea { min-height: 80px; }

/* TABLE */
.table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.table th, .table td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  text-align: left;
}
.table th {
  background: #f0f4f8;
  font-weight: 600;
}