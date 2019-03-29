# Test asserts

    assertCookie
    assertCookieExpired
    assertCookieNotExpired
    assertCookieMissing
    assertDontSee
    assertDontSeeText
    assertExactJson
    assertForbidden
    assertHeader
    assertHeaderMissing
    assertJson
    assertJsonCount
    assertJsonFragment
    assertJsonMissing
    assertJsonMissingExact
    assertJsonMissingValidationErrors
    assertJsonStructure
    assertJsonValidationErrors
    assertLocation
    assertNotFound
    assertOk
    assertPlainCookie
    assertRedirect
    assertSee
    assertSeeInOrder
    assertSeeText
    assertSeeTextInOrder
    assertSessionHas
    assertSessionHasAll
    assertSessionHasErrors
    assertSessionHasErrorsIn
    assertSessionHasNoErrors
    assertSessionDoesntHaveErrors
    assertSessionMissing
    assertStatus
    assertSuccessful
    assertViewHas
    assertViewHasAll
    assertViewIs
    assertViewMissing


# Available Validation Rules

    Accepted
    Active URL
    After (Date)
    After Or Equal (Date)
    Alpha
    Alpha Dash
    Alpha Numeric
    Array
    Bail
    Before (Date)
    Before Or Equal (Date)
    Between
    Boolean
    Confirmed
    Date
    Date Equals
    Date Format
    Different
    Digits
    Digits Between
    Dimensions (Image Files)
    Distinct
    E-Mail
    Exists (Database)
    File
    Filled
    Greater Than
    Greater Than Or Equal
    Image (File)
    In
    In Array
    Integer
    IP Address
    JSON
    Less Than
    Less Than Or Equal
    Max
    MIME Types
    MIME Type By File Extension
    Min
    Not In
    Not Regex
    Nullable
    Numeric
    Present
    Regular Expression
    Required
    Required If
    Required Unless
    Required With
    Required With All
    Required Without
    Required Without All
    Same
    Size
    Starts With
    String
    Timezone
    Unique (Database)
    URL
    UUID


# Response codes

    100 => 'Continue',
    101 => 'Switching Protocols',
    102 => 'Processing',            // RFC2518
    103 => 'Early Hints',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    207 => 'Multi-Status',          // RFC4918
    208 => 'Already Reported',      // RFC5842
    226 => 'IM Used',               // RFC3229
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    307 => 'Temporary Redirect',
    308 => 'Permanent Redirect',    // RFC7238
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Payload Too Large',
    414 => 'URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Range Not Satisfiable',
    417 => 'Expectation Failed',
    418 => 'I\'m a teapot',                                               // RFC2324
    421 => 'Misdirected Request',                                         // RFC7540
    422 => 'Unprocessable Entity',                                        // RFC4918
    423 => 'Locked',                                                      // RFC4918
    424 => 'Failed Dependency',                                           // RFC4918
    425 => 'Too Early',                                                   // RFC-ietf-httpbis-replay-04
    426 => 'Upgrade Required',                                            // RFC2817
    428 => 'Precondition Required',                                       // RFC6585
    429 => 'Too Many Requests',                                           // RFC6585
    431 => 'Request Header Fields Too Large',                             // RFC6585
    451 => 'Unavailable For Legal Reasons',                               // RFC7725
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    506 => 'Variant Also Negotiates',                                     // RFC2295
    507 => 'Insufficient Storage',                                        // RFC4918
    508 => 'Loop Detected',                                               // RFC5842
    510 => 'Not Extended',                                                // RFC2774
    511 => 'Network Authentication Required',                             // RFC6585



# Artisan

      clear-compiled       Remove the compiled class file
      down                 Put the application into maintenance mode
      dump-server          Start the dump server to collect dump information.
      env                  Display the current framework environment
      help                 Displays help for a command
      inspire              Display an inspiring quote
      list                 Lists commands
      migrate              Run the database migrations
      optimize             Cache the framework bootstrap files
      preset               Swap the front-end scaffolding for the application
      serve                Serve the application on the PHP development server
      tinker               Interact with your application
      up                   Bring the application out of maintenance mode
     app
      app:name             Set the application namespace
     auth
      auth:clear-resets    Flush expired password reset tokens
     cache
      cache:clear          Flush the application cache
      cache:forget         Remove an item from the cache
      cache:table          Create a migration for the cache database table
     config
      config:cache         Create a cache file for faster configuration loading
      config:clear         Remove the configuration cache file
     db
      db:seed              Seed the database with records
     debugbar
      debugbar:clear       Clear the Debugbar Storage
     event
      event:generate       Generate the missing events and listeners based on registration
     ide-helper
      ide-helper:eloquent  Add \Eloquent helper to \Eloquent\Model
      ide-helper:generate  Generate a new IDE Helper file.
      ide-helper:meta      Generate metadata for PhpStorm
      ide-helper:models    Generate autocompletion for models
     key
      key:generate         Set the application key
     make
      make:auth            Scaffold basic login and registration views and routes
      make:channel         Create a new channel class
      make:command         Create a new Artisan command
      make:controller      Create a new controller class
      make:event           Create a new event class
      make:exception       Create a new custom exception class
      make:factory         Create a new model factory
      make:job             Create a new job class
      make:listener        Create a new event listener class
      make:mail            Create a new email class
      make:middleware      Create a new middleware class
      make:migration       Create a new migration file
      make:model           Create a new Eloquent model class
      make:notification    Create a new notification class
      make:observer        Create a new observer class
      make:policy          Create a new policy class
      make:provider        Create a new service provider class
      make:request         Create a new form request class
      make:resource        Create a new resource
      make:rule            Create a new validation rule
      make:seeder          Create a new seeder class
      make:test            Create a new test class
     migrate
      migrate:fresh        Drop all tables and re-run all migrations
      migrate:install      Create the migration repository
      migrate:refresh      Reset and re-run all migrations
      migrate:reset        Rollback all database migrations
      migrate:rollback     Rollback the last database migration
      migrate:status       Show the status of each migration
     notifications
      notifications:table  Create a migration for the notifications table
     optimize
      optimize:clear       Remove the cached bootstrap files
     package
      package:discover     Rebuild the cached package manifest
     queue
      queue:failed         List all of the failed queue jobs
      queue:failed-table   Create a migration for the failed queue jobs database table
      queue:flush          Flush all of the failed queue jobs
      queue:forget         Delete a failed queue job
      queue:listen         Listen to a given queue
      queue:restart        Restart queue worker daemons after their current job
      queue:retry          Retry a failed queue job
      queue:table          Create a migration for the queue jobs database table
      queue:work           Start processing jobs on the queue as a daemon
     route
      route:cache          Create a route cache file for faster route registration
      route:clear          Remove the route cache file
      route:list           List all registered routes
     schedule
      schedule:run         Run the scheduled commands
     session
      session:table        Create a migration for the session database table
     storage
      storage:link         Create a symbolic link from "public/storage" to "storage/app/public"
     telescope
      telescope:clear      Clear all entries from Telescope
      telescope:install    Install all of the Telescope resources
      telescope:prune      Prune stale entries from the Telescope database
      telescope:publish    Publish all of the Telescope resources
     vendor
      vendor:publish       Publish any publishable assets from vendor packages
     view
      view:cache           Compile all of the application's Blade templates
      view:clear           Clear all compiled view files
     ziggy
      ziggy:generate       Generate js file for including in build process


