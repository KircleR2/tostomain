<?php

return [
    /*
     * Set trusted proxy IP addresses.
     *
     * Both IPv4 and IPv6 addresses are
     * supported, along with CIDR notation.
     *
     * The "*" character is syntactic sugar
     * within TrustedProxy to trust any proxy
     * that connects directly to your server,
     * a requirement when you cannot know the address
     * of your proxy (e.g. if using ELB or similar).
     *
     */
    'proxies' => env('TRUSTED_PROXIES', '*'),

    /*
     * To trust one or more specific proxies that connect
     * directly to your server, use an array or a string separated by comma of IP addresses:
     */
    // 'proxies' => ['192.168.1.1'],
    // 'proxies' => '192.168.1.1, 192.168.1.2',

    /*
     * Or, to trust all proxies that connect
     * directly to your server, use a "*"
     */
    // 'proxies' => '*',

    /*
     * Which headers to use to detect proxy related data (For, Host, Proto, Port)
     *
     * Options include:
     *
     * - 'HEADER_X_FORWARDED_ALL' (use all x-forwarded-* headers to establish trust)
     * - 'HEADER_FORWARDED' (use the FORWARDED header to establish trust)
     * - 'HEADER_X_FORWARDED_AWS_ELB' (use the X-Forwarded-* headers to establish trust)
     *
     * @link https://symfony.com/doc/current/deployment/proxies.html
     */
    'headers' => 'HEADER_X_FORWARDED_ALL',
]; 