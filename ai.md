
I am searching for a new apartment. Make me a mobile app for that. I would like to see different types of entries in a list (bootstrap list group), sorted by last one first:

- Default fields for entry types:
  - id
    - use a json file to remember the last id
  - date (YYYY-MM-DD)
  - title
- Entry type "common activity", special fields:
  - text
- Entry type "apartment", special fields:
  - details (textarea)
  - a status (dropdown)
    - new
    - current
    - maybe
    - done
  - result (text)
  - url
  - files_id (readonly text, 4 digits with leading zeros, incrementing)
    - use a json file to remember the last id
  - a list of multiple status texts (list group): date (optional) and text

Add typical CRUD functions everywhere. Make it user friendly.

Save the data in /data/data.yml

Also let me use the smartphones cam to add images that are saved in /data/files/FILES_ID/.

Use bootstrap 5.3 and be sure that it looks good on smartphones.

Indent all codes with 2 spaces, put the { on the next line and use Symfony yml.
