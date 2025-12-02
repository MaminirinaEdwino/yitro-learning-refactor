<style>
        /* Styles pour la nouvelle interface du catalogue */
        .catalogue-hero {
            position: relative;
            background: linear-gradient(109deg, #01ae8f, #132f3f);
            padding: 100px 20px 60px;
            text-align: center;
            overflow: hidden;
        }

        .catalogue-hero-content {
            max-width: 900px;
            margin: 0 auto;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .catalogue-hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .catalogue-hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease-out 0.2s;
            animation-fill-mode: both;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .catalogue-hero-content .search-bar {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
            animation: fadeInUp 1s ease-out 0.4s;
            animation-fill-mode: both;
        }

        .catalogue-hero-content .search-bar input {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            border: none;
            border-radius: 6px 0 0 6px;
            outline: none;
            background: #fff;
            color: #333;
        }

        .catalogue-hero-content .search-bar button {
            padding: 12px 20px;
            background-color: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .catalogue-hero-content .search-bar button:hover {
            background-color: #e55a50;
        }

        #hero-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.2;
        }

        .catalogue-main {
            display: flex;
            gap: 20px;
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
            background: #f8fafc;
        }

        .catalogue-sidebar {
            width: 300px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
        }

        .catalogue-sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #132f3f;
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .filter-group select {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            background: #f8fafc;
            color: #333;
        }

        .catalogue-content {
            flex: 1;
        }

        .catalogue-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .catalogue-controls h2 {
            color: #132f3f;
        }

        .sort-options label {
            color: #333;
            margin-right: 10px;
        }

        .sort-options select {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #f8fafc;
            color: #333;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 20px;
        }

        .course-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .course-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .course-card-content {
            padding: 20px;
        }

        .course-card-content h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #132f3f;
        }

        .course-card-content p {
            font-size: 0.9rem;
            color: #444;
            margin-bottom: 8px;
        }

        .course-card-content .price {
            font-weight: 600;
            color: #ff6f61;
        }

        .course-card-content .badge {
            display: inline-block;
            padding: 5px 10px;
            background: #01987a;
            color: #fff;
            font-size: 0.8rem;
            border-radius: 4px;
            margin-top: 10px;
        }

        .course-card-content .btn-primary {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            text-align: center;
            background: #4A90E2;
            color: #f8fafc;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .course-card-content .btn-primary:hover {
            transform: translateY(-2px);
            background-color: #357ABD;
        }

        .categories-section {
            padding: 40px 20px;
            background: #f9f9f9;
        }

        .categories-section h2 {
            font-size: 2rem;
            color: #132f3f;
            text-align: center;
            margin-bottom: 20px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .category-card {
            background: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-card i {
            font-size: 2rem;
            color: #01ae8f;
            margin-bottom: 10px;
        }

        .category-card h3 {
            font-size: 1.2rem;
            color: #132f3f;
        }

        .cta-section {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(109deg, #01ae8f, #2a5366);
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .cta-section .btn-primary {
            margin: 0 10px;
            padding: 12px 24px;
            font-size: 1.1rem;
            background: #ffd700;
            color: #132f3f;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cta-section .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .catalogue-main {
                flex-direction: column;
            }

            .catalogue-sidebar {
                width: 100%;
                position: static;
            }
        }

        @media (max-width: 768px) {
            .catalogue-hero-content h1 {
                font-size: 2rem;
            }

            .catalogue-hero-content p {
                font-size: 1rem;
            }

            .catalogue-hero {
                padding: 80px 15px 40px;
            }

            .course-card img {
                height: 150px;
            }
        }
    </style>