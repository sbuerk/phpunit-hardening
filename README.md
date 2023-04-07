SBUERK PHPUnit Hardening
========================

# Background

PHPUnit decided to remove the `convert*ToException` options with `PHPUnit 10+` in favour
of simply displaying it. This means, following options has been removed:

* convertDeprecationsToExceptions
* convertNoticesToExceptions
* convertErrorsToExceptions
* convertWarningsToExceptions

PHPUnit argued, that deprecations converted to exceptions will hide bugs. Some developers
and Coop maintainers of framework and packages highly disagrees and denies this argumentation,
and personally I'm also doing this.

We worked hard on some packages to ensure that own deprecations are not called in shipped
production code, which is now no longer ensured. That's a big no-no for open source frameworks,
as it highly moves back the responsibility to find and detect these things to the reviewers
and maintainers.

# Mission

The mission of this PHPUnit extension is to reintroduce hard barrier line abilities to avoid slipping
notices, warnings, errors and deprecations into released code versions. Mainly own deprecation triggers
should be ensured to not be called any longer.



> @todo finish README.md and add detailed information

