Act as a Senior Principal Software Architect specialized in Laravel API design and mobile system integrations.

I have an active, fully functional student portal built with Laravel and Filament PHP hosted live. I want to build a mobile app counterpart and need to design a clean, secure RESTful API layer over my existing architecture.

Crucial Constraint: Because my web portal uses Filament (and Livewire), it relies on stateful session authentication. The new mobile API must be stateless, and its authentication setup must seamlessly coexist with my existing Filament admin panel without breaking it.

To help me achieve this, please analyze the architectural details I provide below and generate a structured blueprint for my API integration.

My Tech Stack Goal:
Backend: Laravel + Filament (Existing)

Mobile Frontend: [Insert either React Native or Swift here]

Hosting: Hostinger

Core Modules in My Portal:
[List your core features here, e.g., Student Authentication, Course Management, Results/Grading, Profile Updates, Payment History]

Please provide the following:
Authentication Strategy: Step-by-step implementation for setting up stateful token authentication using Laravel Sanctum, specifically ensuring the Sanctum guards do not conflict with Filament's session guards.

API Route Blueprint: A structured list of endpoints matching my core modules inside routes/api.php, using strict RESTful naming conventions.

Controller Architecture: Advice on whether I should write standard Laravel API controllers from scratch, or if I should utilize a Filament REST API plugin (and the pros/cons of each for my stack).

Sample API Resource & Controller: Provide a concrete example of an Eloquent API Resource and Controller for the [Insert Primary Module, e.g., Student Profile] module, ensuring it outputs clean JSON.

Mobile Fetch Example: A clean code snippet showing how the mobile frontend ([React Native/Swift]) should securely store the token and perform a sample GET request.

Here is the structure of my primary relevant database tables and models for context:
[create relevant migration.md files, schema definitions, or Model structures here if available]