## Web3 LaunchPad Website Proposal


###  Business Name:  Web3 LaunchPad 

### Description:

Web3 LaunchPad is a one-stop destination for anyone looking to learn and work in the Web3 and blockchain industry. Our platform provides curated resources, projects, and job opportunities to help you launch your career in this rapidly growing field.


### Purpose of the CMS:

Web3 LaunchPad's CMS serves as a centralized platform for managing and sharing content related to Web3 and blockchain technology. It is designed to streamline content creation and ensure a consistent and engaging user experience for our audience. The CMS is tailored to the specific needs of our users, providing features such as a user-friendly interface, a variety of content types, user roles, and a database structure that makes it easy to manage content and track user engagement. The purpose of the CMS is to empower users to share their knowledge, connect with others in the community, and pursue opportunities in the Web3 industry.


### User Roles:

- Administrator: Admin users have full control over the CMS, including the ability to manage users, manage content, and oversee site maintenance. They can create, edit, and delete content, as well as manage categories and tags.Comment moderation can take the form of deleting comments, hiding them from public view, or "disemvoweling" comments.Only admin users can moderate comments.- Admin users must have the ability to view all registered users, add users, update users, and delete users.

- Registered Users: Registered users can browse and search for content, submit articles, tutorials, and other content to be published on the platform, and interact with other users through comments, upvotes/downvotes, and social sharing options. They can also CRUD their own content. By clicking on their personal avatar, they will have an optimized personal page to share.

- Visitors: Unregistered visitors can view and search content on the platform.They can submit content, submit a name with their comment.To unlock additional features, they must register for an account.



### Database Structure Description


#### Categories Table

| Field       | Type        | Extra          |
|-------------|-------------|----------------|
| cat_id      | int(3)      | AUTO_INCREMENT |
| cat_title   | varchar(255)| NOT NULL       |
| created_at  | TIMESTAMP   | NOT NULL       |
| updated_at  | TIMESTAMP   | NOT NULL       |

#### Comments Table

| Field          | Type        | Extra          |
|----------------|-------------|----------------|
| comment_id     | int(3)      | AUTO_INCREMENT |
| comment_post_id| int(3)      | NOT NULL       |
| comment_user_id| int(3)      | NOT NULL       |
| comment_content| text        | NOT NULL       |
| comment_status | varchar(255)| NOT NULL       |
| comment_date   | datetime    | NOT NULL       |

#### Posts Table

| Field            | Type        | Extra          |
|------------------|-------------|----------------|
| post_id          | int(3)      | AUTO_INCREMENT |
| post_category_id | int(3)      | NOT NULL       |
| post_title       | varchar(255)| NOT NULL       |
| post_author      | varchar(255)| NOT NULL       |
| post_user        | varchar(255)| NOT NULL       |
| post_date        | datetime    | NOT NULL       |
| update_date      | TIMESTAMP   | NOT NULL       |
| post_image       | LONGTEXT    | NOT NULL       |
| post_content     | text        | NOT NULL       |
| post_tags        | varchar(255)| NOT NULL       |
| post_comment_count|int(11)     | NOT NULL       |
| post_status      | varchar(255)| NOT NULL       |
| post_views_count | int(11)     | NOT NULL       |

#### Users Table

| Field          | Type        | Extra          |
|----------------|-------------|----------------|
| user_id        | int(3)      | AUTO_INCREMENT |
| username       | varchar(255)| NOT NULL       |
| user_email     | varchar(255)| NOT NULL       |
| user_password  | varchar(255)| NOT NULL       |
| user_role      | varchar(255)| NOT NULL       |
| created_at     | timestamp   | NOT NULL       |
