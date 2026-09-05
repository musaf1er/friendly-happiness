USE mischief_outlaws;
-- Change this hash before production. It is the password hash for: ChangeMe!2026
INSERT INTO users (username,email,password_hash,role) VALUES ('admin','admin@mischiefoutlaws.local','$2y$12$95ecBx4Zn63ApQJ19F9hjuCqCwoujAG.DfCZLnmcWwM2QCtfFlTPa','admin');
INSERT INTO officers (name,position,bio,image_path,sort_order) VALUES
('Marcus Hale','President','Keeps the club pointed in the right direction and the standards in the room.','assets/images/officers/officer-president-demo.jpg',1),
('Rhea Cole','Road Captain','Plans the miles, reads the weather, and makes sure everyone gets home.','assets/images/officers/officer-road-captain-demo.jpg',2),
('Dane Mercer','Sergeant at Arms','Looks after the clubhouse and the quiet details that keep it working.','assets/images/officers/officer-saa-demo.jpg',3);
INSERT INTO events (title,event_date,location,description,image_path,status) VALUES
('North Line Run','2026-10-17','Meet at the clubhouse','A full-day ride north. Route and meeting notes go to confirmed riders.','assets/images/events/night-ride-demo.jpg','upcoming'),
('Workshop Night','2026-10-29','The garage','Bring a machine, a question, or a set of hands.','assets/images/events/garage-night-demo.jpg','upcoming');
INSERT INTO gallery (title,image_path,category) VALUES
('Touring 01','assets/images/gallery/touring-01.jpg','touring'),
('Touring 02','assets/images/gallery/touring-02.jpg','touring'),
('Touring 03','assets/images/gallery/touring-03.jpg','touring'),
('Clubhouse 01','assets/images/gallery/clubhouse-01.jpg','clubhouse'),
('Clubhouse 02','assets/images/gallery/clubhouse-02.jpg','clubhouse'),
('Bike 01','assets/images/gallery/bike-01.jpg','bikes'),
('Bike 02','assets/images/gallery/bike-02.jpg','bikes'),
('Bike 03','assets/images/gallery/bike-03.jpg','bikes'),
('Event meet 01','assets/images/gallery/event-gallery-01.jpg','events'),
('Event meet 02','assets/images/gallery/event-gallery-02.jpg','events');
INSERT INTO merchandise (name,description,price,image_path,stock) VALUES
('Workshop tee','Heavy cotton, small chest mark.','28.00','assets/images/merchandise/shirt-demo.jpg',24),
('Road patch','Woven patch for a jacket that has seen some weather.','12.00','assets/images/merchandise/patch-demo.jpg',18);
