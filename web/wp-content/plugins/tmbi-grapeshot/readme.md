# Grapeshot integration

Integrates [Grapeshot](http://www.grapeshot.com/) into WordPress.

## Client-side integration

This plugin supports Grapeshot's client-side integration. If enabled, it adds a script to the head (inline, as the URL is formed based on other JS values), to set the `gs_channels` global variable. The variable can then be sent to DFP, as TMBI Ad Stack currently does.
If server-side integration is enabled as well, client-side integration will be used as a fallback only (e.g. if there's no data available at server side, or for non-single pages).

## Server-side integration

If enabled, for `single` pages, if the `gs_channels` meta field is available, a global targeting param will be set (and TMBI Ad Stack will pick it).
The meta field is handled by a message queue, with a publisher sending a message every time a post is updated, and a consumer calling Grapeshot and storing the results.


### Configuration

The plugin works without any required configuration. It does provide three options (and filters) to change some behaviors:

`grapeshot_url` defaults to `//trustedmediabrands.grapeshot.co.uk/main/channels.cgi?url=`. Change it to use a different Grapeshot server.
`grapeshot_enable_client_side` defaults to `true`. Change it to disable the client-side integration while keeping the plugin active.
`grapeshot_enable_server_side` defaults to `true`. Change it to disable the server-side integration while keeping the plugin active.