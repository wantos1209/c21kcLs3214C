<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #121212; /* Warna latar belakang gelap */
            font-family: 'Arial', sans-serif;
            color: #ffffff; /* Warna teks */
            line-height: 1.6;
        }

        .container {
            max-width: 1200px; /* Lebar maksimum */
            min-height: 100vh; /* Tinggi minimum 100% layar */
            margin: 0 auto; /* Tengah kontainer */
            padding: 20px;
            background-color: #1e1e1e; /* Warna kontainer */
            border-radius: 10px; /* Sudut membulat */
            box-shadow: 0px -4px 8px 5px rgb(255 255 255 / 20%);
        }

        .title {
            width: 100%;
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            color: #ffcc00; /* Warna teks judul */
            margin-bottom: 30px;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .balance-info {
            margin-right: 20px;
            text-align: right;
        }

        .balance-info span {
            display: block;
            font-size: 14px;
            color: #ffcc00;
        }

        .logout-btn {
            background-color: #ffcc00;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #d4a600;
        }

        .kolom-game {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Responsif */
            gap: 20px;
        }

        .kotak {
            background-color: #292929; /* Warna latar kotak */
            border-radius: 8px; /* Sudut membulat */
            overflow: hidden; /* Potong konten yang keluar */
            text-align: center;
            padding: 10px;
            position: relative; /* Untuk tombol hover */
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .kotak:hover {
            transform: translateY(-5px); /* Efek hover */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); /* Efek bayangan hover */
            background-color: rgba(0, 0, 0, 0.9); /* Warna latar redup saat hover */
        }

        .kotak img {
            display: block;
            max-width: 100%; /* Gambar responsif */
            border-radius: 8px;
            margin-bottom: 10px;
            transition: opacity 0.3s ease; /* Animasi untuk meredupkan gambar */
        }

        .kotak:hover img {
            opacity: 0.5; /* Meredupkan gambar saat hover */
        }

        .title-game {
            display: block;
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
            margin-top: 10px;
        }

        /* Tombol "Main" */
        .button-main {
            display: none; /* Sembunyikan tombol awalnya */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffcc00; /* Warna tombol */
            color: #000; /* Warna teks tombol */
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .kotak:hover .button-main {
            display: block;
            opacity: 1; /* Tampilkan tombol */
        }

        /* Responsif */
        @media (max-width: 768px) {
            .container {
                max-width: 90%;
            }
            .title {
                font-size: 28px;
            }
            .header {
                flex-direction: column;
                align-items: flex-end;
            }
            .balance-info {
                text-align: right;
                margin-bottom: 10px;
            }
            .kolom-game {
                grid-template-columns: repeat(3, 1fr); /* Tampilkan 3 kotak per baris */
                gap: 10px; /* Kurangi jarak antar kotak untuk perangkat kecil */
            }
        }

        @media (max-width: 480px) {
            .kolom-game {
                grid-template-columns: repeat(2, 1fr); /* Tampilkan 2 kotak per baris untuk layar sangat kecil */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="balance-info">
                <span>{{ $user["username"] }}</span>
                <span>${{ number_format($user["balance"], 2) }}</span>
            </div>
            <button class="logout-btn">Logout</button>
        </div>
        <div class="title">LIST GAME</div>
        <div class="kolom-game">
            @for ($i = 1; $i <= 30; $i++)
                <div class="kotak" data-url="http://192.168.3.246:5000/">
                    <img src="/front/games/game1.jpg" alt="Game Image">
                    <span class="title-game">Mahjong 3 Wins</span>
                    <button class="button-main">Main</button>
                </div>
            @endfor
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.button-main').on('click', function () {
                const kotak = $(this).closest('.kotak');
                const url = kotak.data('url');
                window.location.href = url;
            });

            $('.logout-btn').on('click', function (e) {
                e.preventDefault(); // Mencegah aksi default tombol

                $.ajax({
                    url: '/logoutlobby', // Route logout
                    type: 'GET',

                    success: function (response) {
                        window.location.href = '/loginlobby'; // Redirect ke halaman login
                    },
                    error: function (xhr, status, error) {
                        console.error('Logout failed:', error); // Log jika gagal
                        alert('Failed to logout. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>
