## Web3 LaunchPad Website Proposal


####  Business Name:  Web3 LaunchPad 

#### Description:

Web3 LaunchPad is a one-stop destination for anyone looking to learn and work in the Web3 and blockchain industry. Our platform provides curated resources, projects, and job opportunities to help you launch your career in this rapidly growing field.


#### Purpose of the CMS:

Web3 LaunchPad's CMS serves as a centralized platform for managing and sharing content related to Web3 and blockchain technology. It is designed to streamline content creation and ensure a consistent and engaging user experience for our audience. The CMS is tailored to the specific needs of our users, providing features such as a user-friendly interface, a variety of content types, user roles, and a database structure that makes it easy to manage content and track user engagement. The purpose of the CMS is to empower users to share their knowledge, connect with others in the community, and pursue opportunities in the Web3 industry.


#### User Roles:

- Administrator: Admin users have full control over the CMS, including the ability to manage users, manage content, and oversee site maintenance. They can create, edit, and delete content, as well as manage categories and tags.

- Registered Users: Registered users can browse and search for content, submit articles, tutorials, and other content to be published on the platform, and interact with other users through comments, upvotes/downvotes, and social sharing options. They can also CRUD their own content. By clicking on their personal avatar, they will have an optimized personal page to share.

- Visitors: Unregistered visitors can view and search content on the platform, but they cannot interact with other users or submit content. To unlock additional features, they must register for an account.



#### Database Structure Description

**1.Users**

- id (int, primary key, auto_increment): Unique identifier for each user.
- username (varchar, 255): The username of the user.
- email (varchar, 255): The email address of the user.
- password (varchar, 255): The hashed password of the user.
- role (varchar, 255): The role of the user (either 'registered' or 'author').
- created_at (datetime): The timestamp when the user was created.
- updated_at (datetime): The timestamp when the user was last updated.
- liked_articles (text): A comma-separated list of article IDs that the user has liked.

**2.Articles**

- id (int, primary key, auto_increment): Unique identifier for each article.
- title (varchar, 255): The title of the article.
- content (text): The content of the article.
- tags (varchar, 255): A comma-separated list of tags associated with the article.
- user_id (int, foreign key): The user who created the article.
- category_id (int, foreign key): The category the article belongs to.
- likes (int): The number of upvotes the article has received.
- created_at (datetime): The timestamp when the article was created.
- updated_at (datetime): The timestamp when the article was last updated.

**3. Categories**

- id (int, primary key, auto_increment): Unique identifier for each category.
- name (varchar, 255): The name of the category.
- description (varchar, 255): A brief description of the category.
- created_at (datetime): The timestamp when the category was created.
- updated_at (datetime): The timestamp when the category was last updated.

 **4.Comments**

- id (int, primary key, auto_increment): Unique identifier for each comment.
- content (text): The content of the comment.
- user_id (int, foreign key): The user who posted the comment.
- article_id (int, foreign key): The article the comment is related to.
- created_at (datetime): The timestamp when the comment was created.
- updated_at (datetime): The timestamp when the comment was last updated.

**During the actual development process, the content may be subject to minor adjustments, with the specific requirements taking priority.*

 

