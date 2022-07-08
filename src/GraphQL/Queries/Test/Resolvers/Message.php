<?php

return function ($rootValue, array $args): string {

    return 'Queries ' . $rootValue['prefix'] . $args['message'];
};
