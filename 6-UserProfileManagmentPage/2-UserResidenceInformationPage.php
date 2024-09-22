<?php
    require_once("../5-UserSignInandRegistration/14-secure.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residence Information</title>
    <style>
        body, html {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8f8f8;
            color: #333;
            overflow: hidden;
        }
        .container {
            display: flex;
            height: 100%;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            padding: 30px 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            margin-left: 240px;
            overflow-y: auto;
            height: 100vh;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        h2 {
            font-size: 20px;
            font-weight: 500;
            margin: 0 0 24px 0;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
        }
        h3 {
            font-size: 18px;
            font-weight: 500;
            margin: 0;
            padding: 15px;
            background-color: #f0f0f0;
        }
        p {
            color: #666;
            font-size: 14px;
            margin: 0 0 10px 0;
        }
        .menu-item {
            padding: 8px 12px;
            margin-bottom: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            border-radius: 4px;
        }
        .menu-item.active {
            background-color: #444;
            font-weight: 500;
        }
        .menu-item svg {
            margin-right: 10px;
        }
        .menu-item a {
            color: white;
            text-decoration: none;
        }
        .residence-container {
            margin-bottom: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .residence-info {
            display: flex;
            padding: 20px;
        }
        .residence-image {
            flex: 0 0 300px;
            margin-right: 20px;
        }
        .residence-image img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .residence-details {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1>Account</h1>
            <p>Manage your account info.</p>
            <div class="menu-item">
                <a href="updated user profile page SysDev.html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Profile
            </a>
            </div>
            <div class="menu-item active">
                <a href="residence page in user managment .html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Residence
            </a>
            </div>
            <div class="menu-item">
                <a href="security page Sys Dev.html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 16V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Security
            </a>
            </div>
        </div>
        <div class="main-content">
            <h2><strong>Residence Information</strong></h2>
            
            <div class="residence-container">
                <h3>Allan Webb Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="/Users/naledimabusela/Downloads/allan webbb.jpg" alt="Allan Webb Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Canterbury, Canterbury Annex, Salisbury, Truro and Winchester.</p>
                        <p><strong>Description:</strong> The smallest hall on campus situated in the beautiful and historic grounds of St. Peter's. It has four residences for men and women, built at the turn of the century, named after British cathedral cities.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Mondays - Common areas, Wednesdays - Bathrooms, Fridays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Courtenay-Latimer Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\Courtenay -latimer hall.jpg" alt="Courtenay-Latimer Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Beit, Jameson and Oriel.</p>
                        <p><strong>Description:</strong> Located in the heart of campus, Courtenay-Latimer Hall is the home of a vibrant group of young women. Living in Courtenay-Latimer Hall is an experience; one that ensures that one becomes part of a special group of well educated, dynamic, fun loving women.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Tuesdays - Common areas, Thursdays - Bathrooms, Saturdays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Desmond Tutu Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\desmond tutu .png" alt="Desmond Tutu Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Ellen Kuzwayo, Amina Cachalia, Calata, Margaret Smith, Hilltop 3 and Oakdene.</p>
                        <p><strong>Description:</strong> This hall was formally known as Hill Top Hall. It offers a diverse and inclusive living environment for students.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Wednesdays - Common areas, Fridays - Bathrooms, Mondays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Drostdy Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\Drostdy_Dining_Hall.jpg" alt="Drostdy Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Allan Gray, Celeste, Graham, and Prince Alfred.</p>
                        <p><strong>Description:</strong> Drostdy Hall has the best geographic situation on campus as it is close to the heart of campus but is also very close to the town. It has both men's and women's houses.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Mondays - Common areas, Wednesdays - Bathrooms, Fridays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Founders Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\Founders'_Dining_Hall.jpg" alt="Founders Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Botha, College, Cory and Matthews.</p>
                        <p><strong>Description:</strong> The Hall is centrally situated on campus and is the oldest Hall on campus for men. Many of the traditional hall activities are still maintained over successive generations.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Tuesdays - Common areas, Thursdays - Bathrooms, Saturdays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Hobson Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\hobson hall.jpg .jpg" alt="Hobson Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Dingemans, Hobson, Livingstone and Milner.</p>
                        <p><strong>Description:</strong> This hall for women combines both an old and new residence style. Hobson Hall is situated among lawns, plane trees and a lovely braai area. It holds academic attainment in high regard.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Wednesdays - Common areas, Fridays - Bathrooms, Mondays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Jan Smuts Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\solomon hall.jpg" alt="Jan Smuts Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Adamson, Atherstone, Jan Smuts and New.</p>
                        <p><strong>Description:</strong> A scenic stream runs through the grounds of this Hall for men and women. Its ample lawns and trees are ideal for informal games and quiet relaxation. Found in an ideal setting near the tennis courts, squash courts and the swimming pool of Rhodes campus.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Thursdays - Common areas, Mondays - Bathrooms, Tuesdays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Miriam Makeba Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\mariam Makeba hall.jpg" alt="Miriam Makeba Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Chris Hani, Piet Retief, Thomas Pringle and Walker.</p>
                        <p><strong>Description:</strong> This is the first side of the "Hill" and has two women's houses and one men's house.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Fridays - Common areas, Tuesdays - Bathrooms, Wednesdays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Kimberley Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\Kimberley_Hall. jpg.jpg" alt="Kimberley Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Cullen Bowles, De Beers, Rosa Parks and Goldfields.</p>
                        <p><strong>Description:</strong> This hall comprises four residences on the second side of the "Hill".</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Mondays - Common areas, Wednesdays - Bathrooms, Fridays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Lilian Ngoyi Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="Z:\res images rhodes\lilian ngoyi hall.jpg" alt="Lilian Ngoyi Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Centenary, Ruth First, Joe Slovo and Victoria Mxenge.</p>
                        <p><strong>Description:</strong> Lilian Ngoyi Hall is a relatively new Hall on campus and was constituted in 2009. Being a new Hall, we are making use of the unique opportunity to develop our own ethos, atmosphere and traditions. Founded on the principles of strength, love and courage, we pledge to infuse a spirit of engagement, responsibility and service in our community.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Tuesdays - Common areas, Thursdays - Bathrooms, Saturdays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>Nelson Mandela Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="/api/placeholder/300/200" alt="Nelson Mandela Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> Stanley Kidd, Adelaide Tambo, Guy Butler and Helen Joseph.</p>
                        <p><strong>Description:</strong> The Nelson Mandela Hall is the youngest Hall on campus comprising of both men's and women's houses. The modern facilities, set in awesomely landscaped gardens, combined with the energetic and young wardening team, make Nelson Mandela Hall a great place to be.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Wednesdays - Common areas, Fridays - Bathrooms, Mondays - Exterior</p>
                    </div>
                </div>
            </div>

            <div class="residence-container">
                <h3>St Mary Hall</h3>
                <div class="residence-info">
                    <div class="residence-image">
                        <img src="/api/placeholder/300/200" alt="St Mary Hall" />
                    </div>
                    <div class="residence-details">
                        <p><strong>Consists of:</strong> John Kotze, Lilian Britten, Olive Schreiner and Phelps.</p>
                        <p><strong>Description:</strong> The Hall comprises four residences attractively grouped around a central Dining Hall in lovely gardens. The Hall caters for both undergraduate and postgraduate female students.</p>
                        <p><strong>Weekly Maintenance Schedule:</strong> Thursdays - Common areas, Mondays - Bathrooms, Tuesdays - Exterior</p>
                    </div>
                </div>
            </div>
<div>
    <p> space </p>
</div>
        </div>
    </div>
</body>
</html>