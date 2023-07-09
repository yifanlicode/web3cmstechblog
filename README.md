## Web3 LaunchPad Platform

Web3 LaunchPad is a PHP CRUD-based Content Management System (CMS) for a fictional client using a variety of the technologies I have learned in the past few months.

The platform is built with PHP, Bootstrap5, SQL, and AJAX.

## User Roles:

![CMSERD.drawio.png](https://s2.loli.net/2023/07/09/kbfl7snB98Z6qQR.png)

- Administrator: Admin users have full control over the CMS, including the ability to manage users, manage content, and oversee site maintenance. They can create, edit, and delete content, as well as manage categories and tags.

- Registered Users: Registered users can browse and search for content, submit articles, tutorials, and other content to be published on the platform, and interact with other users through comments, upvotes/downvotes, and social sharing options. They can also CRUD their own content. By clicking on their personal avatar, they will have an optimized personal page to share.

- Visitors: Unregistered visitors can view and search content on the platform, but they cannot interact with other users or submit content. To unlock additional features, they must register for an account.

## Features

### User Signup and Login

![image.png](https://s2.loli.net/2023/07/09/AE8s3dPbHk6oyw1.png)

### Content Management System CRUD

Article(CRUD)
![image.png](https://s2.loli.net/2023/07/09/w35bFW46VnjCdRt.png)

Support WYSIWYG editing. A few JS WYSIWYG libraries: TinyMCE, CKEditor, Summernote, WYSIHTML5, Quill.
![image.png](https://s2.loli.net/2023/07/09/XwEA59vduKosZFe.png)

Catories & Tag(CRUD)
![image.png](https://s2.loli.net/2023/07/09/xeHPOBwRKI1N74T.png)

Commit:Embedded CAPTCHAs, like reCAPTCHA
![image.png](https://s2.loli.net/2023/07/09/hoRFEA43aJksfVP.png)

### Image Upload

![image.png](https://s2.loli.net/2023/07/09/TcxWEhZb9Y5KjOk.png)

- Add an optional image to the article Cover Image.
- Remove an associated image from a page.
- Images are automatically resized when uploaded.

### Content Search

![image.png](https://s2.loli.net/2023/07/09/9LHn3j7OkfgKYbT.png)

- Search for specific pages by keyword using a search form.
- Search for specific pages by keyword while limiting the search results to a specific category of pages using a dropdown menu.
- Search results are [paginated](http://www.smashingmagazine.com/2007/11/pagination-gallery-examples-and-good-practices/).

### Sort by Function

Blogs can be sorted by Date/Title.. by using Javascript

![image.png](https://s2.loli.net/2023/07/09/fmhX4OCGl3Lyn7c.png)

### Admin Panel(Users/Articles/Commits/Categories CRUD)

![image.png](https://s2.loli.net/2023/07/09/6tvn25mTg4wxRZ8.png)

- Usernames & passwords are stored in a users table with CRUD admin access.
- Passwords stored in the user table are encrypted (hashed and salted). Login functionality must also be implemented that supports these hashed/salted passwords.
  ![image.png](https://s2.loli.net/2023/07/09/c46IeXlrOphRLKs.png)

### Validation and Security

![image.png](https://s2.loli.net/2023/07/09/XN87oU4fiYkFDw6.png)

- Implemented validation rules that are used on the data provided when creating and updating pages.
- Sanitized and validated the numericality of all ids retrieved from GET or POST parameters used in SQL queries.
- Sanitized all strings retrieved from GET or POST parameters to prevent HTML injection attacks.

### Layout and Design

- BootStrap5
- Create page permalink URLs that include ids and are SEO friendly.

## Resources:

1. [How to CMS](https://evanli.notion.site/How-to-CMS-69ab8e3077254c3ca1cfc65eab141335)
2. [Sampe_PHP_CMS_Project](https://www.stungeye.com/school/cms/) 3.[Tutorial on Udemy](https://www.udemy.com/course/php-for-complete-beginners-includes-msql-object-oriented/learn/lecture/2413046?start=0)
