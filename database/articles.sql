INSERT INTO Articles (title, content, user_id, category_id, upvotes, created_at, updated_at)
VALUES 
('Article Title', 'Article Content', 1, 1, 0, NOW(), NOW());


INSERT INTO Categories (name, description, created_at, updated_at)
VALUES 
('Blockchain Technology', 'Discuss the latest blockchain technologies, such as Ethereum, Bitcoin, etc.', NOW(), NOW()),
('Investment', 'Discuss investment strategies and opportunities for women in the blockchain field.', NOW(), NOW()),
('Career Development', 'Career paths and success stories', NOW(), NOW()),
('Latest Events', 'Community events and resources in the blockchain industry.', NOW(), NOW()),
('Friendship Community', 'A place for making friends and connecting with others in the blockchain industry.', NOW(), NOW()),
('Job Opportunities', 'Available job positions in the blockchain industry.', NOW(), NOW());

INSERT INTO Comments (content, user_id, article_id, created_at, updated_at)
VALUES
('This is an interesting article!', 2, 1, NOW(), NOW()),
('I have learned a lot from this article, thank you for sharing.', 3, 1, NOW(), NOW()),
('I really enjoy reading your articles, keep up the great work!', 2, 2, NOW(), NOW()),
('This article was extremely helpful, thank you for writing it.', 3, 2, NOW(), NOW());


INSERT INTO Users (username, email, password, role, created_at, updated_at)
VALUES 
('admin', 'admin@localhost', '123', 'admin', NOW(), NOW()),
('yifan', 'liyifan2019@gmail.com', '123', 'author', NOW(), NOW()),
('dina', 'atom1016@gmail.com', '123', 'registered', NOW(), NOW());
