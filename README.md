# PHP-2FA

A small php file that integrate all the necessary for 2FA (TOTP) use in your project

## Getting Started

### Installation

* attach the file into your PHP project with require or require_once
```
require_once("path_to_file.php");
```

### Use

* Use 1 of theses commands :
```
$my_var_string=p2fa_generate(32); //generate 32 chars key
$my_var_array=p2fa_getTOTP($my_secret); //retrieve previous, actual and next correct 2FA code
```

## Help

* Contact me if you have issue on this code

## Author

Nicolas POTIER

* my softwares : https://soft.potier.me/
* my CV page : https://nicolas-cv.potier.me

## Version History

* 1.0
    * Initial Release

## License

This project is licensed under the MIT (Massachusetts Institute of Technology) License - see the LICENSE.md file for details
