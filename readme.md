*   [Installation/Download](http://localhost/SociaLabs/framework/download)
*   [Documentation<span class="caret"></span>](#)
    *   [Entities](http://localhost/SociaLabs/framework/entities)
    *   [Database](http://localhost/SociaLabs/framework/database)
    *   [CSS](http://localhost/SociaLabs/framework/css)
    *   [Javascript](http://localhost/SociaLabs/framework/javascript)
    *   [Views](http://localhost/SociaLabs/framework/views)
    *   [Forms](http://localhost/SociaLabs/framework/forms)
    *   [Ajax](http://localhost/SociaLabs/framework/ajax)
    *   [Pages](http://localhost/SociaLabs/framework/pages)
    *   [Languages](http://localhost/SociaLabs/framework/languages)
    *   [Metatags](http://localhost/SociaLabs/framework/metatags)
    *   [Menus](http://localhost/SociaLabs/framework/menus)
    *   [Alerts](http://localhost/SociaLabs/framework/alerts)
    *   [Plugins](http://localhost/SociaLabs/framework/plugins)

<div class="col-md-9">

*   ## Entities

    <div class="alert alert-info">

    Entities are the basic building blocks of the SociaLabs system. Any data that needs to be stored and retrieved can be saved as an entity.

    For example, site users are entities. User data is stored as metadata.

    </div>

    **To create a new user entity:**

    <pre class="prettyprint">$user = new User;
    $user->first_name = "Shane";
    $user->last_name = "Barron";
    $user->email = "admin@socialabs.co";
    $user->save();
                    </pre>

    <div class="alert alert-info">To define a new Entity type extend the Entity class and define the entity type in the constructor.</div>

    <pre class="prettyprint">class Blog extends Entity {
        public function __construct() {
            $this->type = "Blog";
        }
    }
                    </pre>

    *   ### Classes

        *   #### Entity

            <div class="alert alert-info">The Entity class is the base class for all Entities. Every entity can utilize the following methods.</div>

            <pre class="prettyprint">$user = new User;
            $icon = $user->icon(SMALL,"img-responsive");
            $url = $user->getURL();
            $user->first_name = "Shane Barron";
            $user->save();
            $user->delete();
                                        </pre>

        *   #### Activity

            <div class="alert alert-info">Adds information to the activity stream.</div>

            <pre class="prettyprint">$owner_guid = getLoggedInUserGuid();
            $owner = getEntity($owner_guid);
            $message = translate("owner:avatar:new",$owner->full_name);
            new Activity($owner_guid,$message);
                                        </pre>

        *   #### ActivityTab

            <div class="alert alert-info">Creates a new tab for the activity stream. Default tabs are "Mine", and "Friends"</div>

            **Contents of the tab are to be located in views/activity/$name**

            <pre class="prettyprint">new ActivityTab("all",500);

            removeActivityTab("all");
                                        </pre>

        *   #### RegistrationField

            <div class="alert alert-info">Adds a registration field to the registration form.</div>

            <pre class="prettyprint">$name = "first_name";
            $label = "First Name";
            $type = "text";
            new RegistrationField($name,$label,$type);
                                        </pre>

        *   #### Setting

            <pre class="prettyprint">new Setting("show_admin_panel", "dropdown", array(
                "no" => "No"
            ));

            Setting::set("show_admin_panel","no");

            $setting = Setting::get("show_admin_panel");
                                        </pre>

        *   #### Users

            *   ##### isOwner()

                <div class="alert alert-info">Checks if a user is the owner of an entity. Entities identify their owner with the owner_guid metadata.</div>

                <pre class="prettyprint">$guid = Vars::get("guid");
                $blog = getEntity($guid);
                $user = getLoggedInUser();
                if (isOwner($blog,$user)) {
                    // User owns blog, so do stuff here
                }
                                                    </pre>

            *   ##### loggedIn()

                <div class="alert alert-info">Returns whether or not someone is logged in.</div>

                <pre class="prettyprint">if (loggedIn()) {
                    // User is logged in, so do stuff here
                }
                                                    </pre>

            *   ##### loggedOut()

                <div class="alert alert-info">Returns whether or not someone is logged out.</div>

                <pre class="prettyprint">if (loggedOut()) {
                    // User is logged out, so do stuff here
                }
                                                    </pre>

            *   ##### getLoggedInUser()

                <div class="alert alert-info">Returns logged in user entity.</div>

                <pre class="prettyprint">$user = getLoggedInUser();
                                                    </pre>

            *   ##### getLoggedInUserGuid()

                <div class="alert alert-info">Returns guid of logged in user.</div>

                <pre class="prettyprint">$user_guid = getLoggedInUserGuid();
                                                    </pre>

            *   ##### gateKeeper()

                <div class="alert alert-info">Used to prevent non logged in users from accessing content. Non logged in users will be forwarded to home page.</div>

                <pre class="prettyprint">gateKeeper();
                                                    </pre>

            *   ##### reverseGateKeeper()

                <div class="alert alert-info">Used to prevent logged in users from accessing content. Logged in users will be forwarded to home page.</div>

                <pre class="prettyprint">reverseGateKeeper();
                                                    </pre>

            *   ##### adminGateKeeper()

                <div class="alert alert-info">Used to prevent non admins from accessing content. Non admin users will be forwarded to home page.</div>

                <pre class="prettyprint">adminGateKeeper();
                                                    </pre>

            *   ##### adminLoggedIn()

                <div class="alert alert-info">Used to run code only when admin logged in.</div>

                <pre class="prettyprint">if (adminLoggedIn()) {
                    // Admin logged in, so do something here
                }
                                                    </pre>

            *   ##### notifyUser()

                <div class="alert alert-info">Used to send notification and email to a user.</div>

                <pre class="prettyprint">$guid = getLoggedInUserGuid();
                $message = "This is a system message."; 
                $link = getURL()."profile/$guid";
                notifyUser($guid,$message,$link);
                                                    </pre>

            *   ##### getOnlineUsers()

                <div class="alert alert-info">Returns an array of all online users.</div>

                <pre class="prettyprint">$online_users = getOnlineUsers();
                                                    </pre>

            *   ##### removeRegistrationField()

                <pre class="prettyprint">removeRegistrationField("first_name");
                                                    </pre>

            *   ##### sendEmail()

                <div class="alert alert-info">Sends email to a user.</div>

                <pre class="prettyprint">sendEmail(array(
                    "to" => array(
                        "name" => "Shane Barron",
                        "email" => "admin@socialabs.co"
                    ),
                    "from" => array(
                        "name" => "George Jones",
                        "email" => "george@socialabs.co"
                    ),
                    "subject" => "Message Subject",
                    "body" => "Message Body",
                    "html" => true
                ));
                                                    </pre>

    *   ### Methods

        *   #### getEntities()

            <div class="alert alert-info">Returns an array of entities from the database.</div>

            <pre class="prettyprint">$users = getEntities(array(
                "type"=>"User"
            ));
                                        </pre>

            **Other arguments can be passed to the getEntities() method:**

            <pre class="prettyprint">$users = getEntities(array(
                "type"=>"User", 
                "limit"=>10,
                "offset"=>10,
                "metadata_name"=>"email",
                "metadata_value"=>"admin@socialabs.co",
                "metadata_name_value_pairs"=>array(
                    array(
                        "name"=>"first_name",
                        "value"=>"Shane",
                        "operand"=>"="
                    ),
                    array(
                        "name"=>"last_name",
                        "value"=>"Barron",
                        "operand"=>"="
                    )
                ),
                "metadata_name_value_pairs_operand"=>"AND"
            ));
                                        </pre>

        *   #### getEntity()

            <div class="alert alert-info">This method works exactly like getEntities(), but only returns the first entity in the list.</div>

        *   #### listEntities()

            <div class="alert alert-info">Takes the same arguments as getEntities(), but instead of returning an array, returns an HTML formatted list of entities. The way an entity appears is determined by "view/entity/EntityName"</div>

            **For example, to define how a Blog entity will appear in the list:**

            <pre class="prettyprint">$guid = Vars::get("guid"); // Get the guid of the entity
            $entity = getEntity($guid); // Get the entity from the database using it's guid
            $icon = $entity->icon(SMALL,"media-object"); // Get the entity's icon, applying the "media-object" css class
            $url = $entity->getURL(); // Get the url of the entity (for full view)
            echo <<<HTML
            <div class="media">
              <div class="media-left">
                <a href="$url">
                  $icon
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading">$entity->title</h4>
                $entity->description
              </div>
            </div>
            HTML;
                                        </pre>

        *   #### viewEntityList()

            <div class="alert alert-info">Converts an array of entities to an HTML formatted list. The way an entity appears is determined by "view/entity/EntityName"</div>

        *   #### classGateKeeper()

            <div class="alert alert-info">Used to make sure an entity is an instantiation of a certain class. If the entity isn't an instantiation of the provided class, the site will forward to the home page.</div>

            **Example:**

            <pre class="prettyprint">$guid = Vars::get("guid");
            $entity = getEntity($guid);
            classGateKeeper($entity,"User");
                                        </pre>

</div>
