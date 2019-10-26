# SloFurs Events Website

This is a website tailored for creating and managing events, more specifically aimed at the furry community. Users create an account, fill out their details and then register for events they wish to attend. This makes it easier for the user as they don't need to submit their personal info every time they want to attend an event. The administration is tier-based, meaning some users get more privileges than others and as such can edit or view more.
The site is created and tailored to the specific needs of SloFurs, but the goal is to make it as adaptable to different needs, so that it can be widely used by other, non-Slovenian, furry communities.

## Getting Started

All website files are included in the project, however before the website will start to function a database needs to be set-up. The database template is available [on dbDiagram](https://dbdiagram.io/d/5ca7268cf7c5bb70c72f8687). You will need to manually edit the account privileges for the first user created in the database.

## Built With

* [W3.css](https://www.w3schools.com/w3css/) - W3.css for styling
* [jQuery](https://jquery.com/) - JavaScript library

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e87dc0cd44694747b3a5fa19ac5286ae)](https://www.codacy.com/manual/Pur3Bolt/SloFurs?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Pur3Bolt/SloFurs&amp;utm_campaign=Badge_Grade)

### Dependencies

* [Composer](https://getcomposer.org/) - Dependency manager
* [spyc](https://github.com/mustangostang/spyc) - Dependency of i18n for using the YAML format
* [mPDF](https://github.com/mpdf/mpdf) - PDF generator
* [SendGrid](https://sendgrid.com) - API for sending emails
* [i18n](https://github.com/Philipp15b/php-i18n) - Website language manager

## Authors

* **Matevž Špacapan** - *Initial work* - [Pur3Bolt](https://github.com/Pur3Bolt)

See also the list of [contributors](https://github.com/Pur3Bolt/SloFurs/graphs/contributors) who participated in this project.

## License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE.md](LICENSE.md) file for details.
