Monitoring Bundle
=================

Integrates all monitoring of Becklyn apps.


Config
------

* `project_name`: the name of the project
* `trackjs`: the token for the integration with TrackJS.


Features
--------

*   The bundle automatically adds a `<!-- uptime monitor: $project_name -->` comment to all HTML responses. Use this for integration into uptime monitors.
*   If you set a `trackjs` token, you can include the monitoring JS:
    
    ```twig
    {% block javascripts %}
        {{- monitoring_embed() -}}
    
        {# .. your other JS imports #}
    {% endblock %}
    ```


Assets Bundle Integration
-------------------------

This bundle registers a `@monitoring` namespace in the becklyn assets bundle.
