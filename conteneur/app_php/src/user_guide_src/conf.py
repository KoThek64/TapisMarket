# Configuration file for the Sphinx documentation builder.

import os
import sys
import sphinx_rtd_theme

project = 'Marketplace Tapis'
copyright = '2025, Équipe 4.02'
author = 'Aignelot, Bernard, Filmont, Lachaise, Plu'
version = '1.0'
release = '1.0.0'

extensions = [
    'sphinx.ext.autodoc',
    'sphinx.ext.viewcode',
    'sphinx.ext.githubpages',
    'sphinx_rtd_theme',       
    'sphinxcontrib.phpdomain' 
]

templates_path = ['_templates']
exclude_patterns = ['_build', 'Thumbs.db', '.DS_Store']
language = 'fr'

html_theme = 'sphinx_rtd_theme'
html_static_path = ['_static']
html_favicon = '_static/logo.png' 
html_css_files = ['custom.css']

# C'est ICI que l'on configure le comportement de la sidebar
html_theme_options = {
    'logo_only': False,
    'prev_next_buttons_location': 'bottom',
    'style_external_links': False,
    'vcs_pageview_mode': '',
    'style_nav_header_background': '#111827',
    'collapse_navigation': False,  # Le menu reste ouvert
    'sticky_navigation': True,     # La sidebar suit le scroll
    'navigation_depth': 4,         # Profondeur des titres dans le menu
    'includehidden': True,
    'titles_only': False           # Affiche les titres internes des pages
}
