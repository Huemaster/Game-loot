DROP DATABASE IF EXISTS gameloot;

CREATE DATABASE gameloot;

USE gameloot;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,  
    username VARCHAR(255) NOT NULL UNIQUE,          
    email VARCHAR(255) NOT NULL UNIQUE,      
    password VARCHAR(255) NOT NULL,          
    profile_pic_url VARCHAR(255),            -- URL to the user's profile picture  / under review
    join_date DATETIME DEFAULT CURRENT_TIMESTAMP  
);

CREATE TABLE Games (
    game_id INT AUTO_INCREMENT PRIMARY KEY,    
    game_title VARCHAR(255) NOT NULL,           
    platform VARCHAR(255),                      
    category VARCHAR(255),                      
    release_date DATE,                          
    description TEXT                          
);

CREATE TABLE Discussions (
    discussion_id INT AUTO_INCREMENT PRIMARY KEY,  
    game_id INT NOT NULL,                          
    user_id INT NOT NULL,                          
    discussion_title VARCHAR(255) NOT NULL,        
    active_deal TEXT DEFAULT "Currently no discounts on this game uploaded.", -- Info on current deals  / under review
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE,        
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE         
);

CREATE TABLE User_Discussions (
    user_id INT NOT NULL,                           -- References the user who joined the discussion
    discussion_id INT NOT NULL,                     -- References the discussion the user joined
    PRIMARY KEY (user_id, discussion_id),           -- Composite primary key to ensure uniqueness
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,  -- Foreign key to Users
    FOREIGN KEY (discussion_id) REFERENCES Discussions(discussion_id) ON DELETE CASCADE  -- Foreign key to Discussions
);


CREATE TABLE Deals (
    deal_id INT AUTO_INCREMENT PRIMARY KEY,    -- Unique ID for each deal
    game_id INT,                               -- Reference to the game
    user_id INT,                               -- ID of the user who submitted the deal
    platform VARCHAR(255),                      -- Platform where the game is on sale 
    category VARCHAR(255),                   -- Category of the deal 
    original_price DECIMAL(10, 2),             -- Original price of the game
    sale_price DECIMAL(10, 2),                 -- Sale price of the game
    expiry_date DATE,                          -- Expiration date of the deal
    deal_url VARCHAR(255),                     -- URL or link to the deal
    submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,  -- Date the deal was submitted
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,  -- Foreign key to Users
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE   -- Foreign key to Games
);

CREATE TABLE Wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each wishlist item
    user_id INT,                                 -- ID of the user who added the game to their wishlist
    game_id INT,                                 -- ID of the game in the wishlist
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,   -- Foreign key to Users
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE,   -- Foreign key to Games
    UNIQUE (user_id, game_id)                    -- Prevent duplicate wishlist entries
);

CREATE TABLE Comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,          -- Unique ID for each comment
    user_id INT,                                        -- ID of the user who made the comment
    discussion_id INT,                                  -- ID of the discussion this comment belongs to (optional, to group comments)
    comment_text TEXT,                                  -- The text content of the comment
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,        -- Date and time the comment was posted
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,   -- Foreign key to Users
    FOREIGN KEY (discussion_id) REFERENCES Discussions(discussion_id) ON DELETE CASCADE -- Foreign key to Discussions (if you have this table)
);


CREATE TABLE DealAlerts (
    alert_id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique ID for each alert
    user_id INT,                               -- ID of the user who set the alert
    game_id INT,                               -- ID of the game the user is watching
    alert_type ENUM('email', 'push_notification'),  -- Type of alert (e.g., email, push notification)
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,   -- Foreign key to Users
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE    -- Foreign key to Games
);

SET GLOBAL event_scheduler = ON;
CREATE EVENT IF NOT EXISTS delete_expired_deals
ON SCHEDULE EVERY 1 DAY
DO
DELETE FROM deals WHERE expiry_date < NOW();

