# IP-Gatekeeper

Note: Current Under Development

This is a poor man's way to keep unwanted eyes off of a project.  It was developed out of a need to allow client access to an unfinished product while still keeping out the general public.  Rather than granting access through some sort of login mechanism, this tool analyzes the user's IP address to grant or deny access.  While obviously not as secure as a login mechanism, this tool serves its purpose by providing a simply way of keeping out unwanted users, while granting access to a client without the need to remember yet another username and password.


## How it Works
Content coming soon...
[Describe the process of what goes on behind the scenes]


## How to Use
Content coming soon...


## Future Development
* Convert the tool into a single-page app
  * Use routes to create "pages" for `/authorize` and `/admin`
    * Link to these areas
  * This will allow end-user to control all aspects of the app (i.e. password, redirect URLs, etc.) from one place
  * Access to the `/admin` page should require a password