INSERT INTO roles (role_name) VALUES
('Admin'),
('Customer'),
('Guest');

INSERT INTO users (name, email, password_hash, role_id, eco_points_balance) VALUES
('Alice Johnson', 'alice@dragonstone.com', 'hashed_password_1', 1, 150),
('Bob Smith', 'bob@dragonstone.com', 'hashed_password_2', 2, 100);

INSERT INTO categories (category_name) VALUES
('Cleaning & Household'),
('Kitchen & Dining'),
('Home Decor & Living'),
('Bathroom & Personal care'),
('Lifestyle & Wellness'),
('Kids & Pets'),
('Outdoor & Garden');

INSERT INTO products (name, description, price, stock_quantity, carbon_factor, category_id, image_url)
VALUES
('Bamboo Toothbrush', 'Eco-friendly bamboo toothbrush with compostable packaging.', 45.00, 120, 1.2, 4, 'images/toothbrush.jpeg'),
('Beeswax Wraps', 'Reusable beeswax wraps for food storage.', 90.00, 80, 0.8, 2, 'images/beeswax.jpeg'),
('Organic Cotton Towels', 'Soft and absorbent organic cotton towels.', 180.00, 60, 2.1, 4, 'images/towels.jpeg'),
('Soy Wax Candle', 'Handmade soy wax candle with essential oils.', 120.00, 75, 1.5, 3, 'images/candle.jpeg'),
('Reusable Water Bottle', 'Stainless steel water bottle made from recycled materials.', 150.00, 50, 1.1, 5, 'images/bottle.jpeg'),
('Wooden Toy Set', 'FSC-certified wooden toys for kids.', 250.00, 40, 2.8, 6, 'images/toy.jpeg'),
('Compost Bin', 'Kitchen compost bin made from recycled plastic.', 300.00, 30, 3.2, 7, 'images/bin.jpeg');

INSERT INTO orders (user_id, order_date, payment_status, shipping_status, total_amount) VALUES
(1, '2024-10-01 10:00:00', 'Paid', 'Shipped', 135.00),
(2, '2024-10-02 11:30:00', 'Pending', 'Pending', 90.00);

INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(1, 1, 2, 45.00),
(1, 3, 1, 180.00),
(2, 2, 1, 90.00);

INSERT INTO subscriptions (user_id, product_id, frequency, next_billing_date) VALUES
(1, 2, 'Monthly', '2024-11-01'),
(2, 1, 'Weekly', '2024-10-09');

INSERT INTO community_posts (user_id, title, content) VALUES
(1, 'Healthy Eating Tips', 'Learn how to make healthy food choices.'),
(2, 'Sustainability Goals', 'Set sustainability goals for your life.');

INSERT INTO comments (post_id, user_id, content) VALUES
(1, 2, 'Thanks for the tips!'),
(2, 1, 'Great post!');

INSERT INTO reviews (user_id, product_id, rating, comment) VALUES
(1, 1, 5, 'Love this toothbrush! Very eco-friendly.'),
(2, 2, 4, 'Beeswax wraps are great but a bit pricey.');

INSERT INTO eco_point_transactions (user_id, points, source, created_at) VALUES
(1, 50, 'Purchase', '2024-10-01 10:05:00'),
(2, 30, 'Review', '2024-10-02 12:00:00');