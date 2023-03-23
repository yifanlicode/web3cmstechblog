

## CMS Project Proposal for Women in Web3

####  Business Name:  Women in Web3 Tech Blog

#### Description:

Women in Web3 is a not-for-profit organization dedicated to empowering and supporting women in the rapidly growing Web3 and blockchain technology space. They aim to create a strong community that fosters learning, collaboration, and growth by providing resources, networking opportunities, and mentorship. To effectively manage and share their wealth of knowledge, the organization requires a web-based CMS tailored to their specific needs.

#### Purpose of the CMS:

The Women in Web3 organization requires a modern, user-friendly, and minimalist content management system (CMS) to manage and organize a variety of content, such as technical tutorials, web3 operational updates, job listings, success stories, and more. The CMS will serve as a centralized platform for managing the organization's online presence, streamlining content creation, and ensuring a consistent and engaging user experience for its audience.


#### User Roles:

- Administrator: Admin users have full control over the CMS, including the ability to manage users, manage content, and oversee site maintenance. They can create, edit, and delete content, as well as manage categories and tags.

- Authors: Authors are verified content creators who can submit articles, tutorials, and other content to be published on the platform. They can CRUD their content. By clicking on their personal avatar, they will have an optimized personal page to share.

- Registered Users: Registered users can browse and search for content and interact with authors and other users through comments, upvotes/downvotes, and social sharing options.

- Visitors: Unregistered visitors can view and search content on the platform, but they cannot interact with other users or submit content. To unlock additional features, they must register for an account.

 

#### Database Structure Description

The proposed database structure for the Women in Web3 CMS will consist of the following tables:
![](https://ibb.co/rdmv7Vx)

**1.Users**

\-    id (int, primary key, auto_increment): Unique identifier for each user.

\-    username (varchar, 255): The username of the user.

\-    email (varchar, 255): The email address of the user.

\-    password (varchar, 255): The hashed password of the user.

\-    role (varchar, 255): The role of the user (either 'registered' or 'author').

\-    created_at (datetime): The timestamp when the user was created.

\-    updated_at (datetime): The timestamp when the user was last updated.

**2. Articles**

\-    id (int, primary key, auto_increment): Unique identifier for each article.

\-    title (varchar, 255): The title of the article.

\-    content (text): The content of the article.

\-    user_id (int, foreign key): The user who created the article.

\-    category_id (int, foreign key): The category the article belongs to.

\-    upvotes (int): The number of upvotes the article has received.

\-    created_at (datetime): The timestamp when the article was created.

\-    updated_at (datetime): The timestamp when the article was last updated.

**3. Categories**

\-    id (int, primary key, auto_increment): Unique identifier for each category.

\-    name (varchar, 255): The name of the category.

\-    description (varchar, 255): A brief description of the category.

\-    created_at (datetime): The timestamp when the category was created.

\-    updated_at (datetime): The timestamp when the category was last updated.

 **4.Comments**

\-    id (int, primary key, auto_increment): Unique identifier for each comment.

\-    content (text): The content of the comment.

\-    user_id (int, foreign key): The user who posted the comment.

\-    article_id (int, foreign key): The article the comment is related to.

\-    created_at (datetime): The timestamp when the comment was created.

\-    updated_at (datetime): The timestamp when the comment was last updated.

**During the actual development process, the content may be subject to minor adjustments, with the specific requirements taking priority.*

 

I'm truly passionate about this project. I am committed to creating a CMS that not only meets the organization's needs but also delivers a seamless, engaging, and valuable experience for all users.
