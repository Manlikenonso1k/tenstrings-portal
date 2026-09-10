> **Role:** You are an expert Laravel and Filament PHP developer.
> **Context:** I am building a student portal using Laravel and Filament. I have an existing CSV import feature for importing students and their payment records. My database includes models for Student, Course (which has a base_fee column), and Branch (names include: Ajah, Agege, Ikeja, and Festac).
> **The Problem:** My current CSV import is not calculating the outstanding balance correctly. If a student pays a partial amount, the system incorrectly flags them as fully paid. Additionally, I need to apply a dynamic markup to specific branches (like Ajah), but I want this markup to be controllable via the Filament Admin panel rather than hardcoded.
> **Requirements:**
> Please provide the code updates needed (Migrations, Filament Resources, Model logic, and Importer logic) to implement the following:
> **1. Dynamic Branch Markup via Admin Panel**
>  * Generate a migration to add two new columns to the branches table: is_luxury_branch (boolean, default false) and markup_percentage (decimal or integer, default 0).
>  * Provide the Filament form schema for the BranchResource so the Admin can use a Toggle (is_luxury_branch) and a Text Input (markup_percentage) to turn the markup on/off and update the percentage at any time.
> **2. Dynamic Fee Calculation**
>  * Add a Model Accessor/Attribute on the Student or Course model to calculate the "Total Course Fee".
>  * The logic: If the student's assigned branch has is_luxury_branch set to true, take the course's base_fee and add the branch's markup_percentage to it. If false, just use the standard base_fee.
> **3. Accurate Balance Calculation on CSV Import**
>  * When the CSV imports a student record (e.g., Name: Joe, Course: Advanced Diploma in Music Performance, Amount Paid: 100,000 NGN), the Filament\Actions\Imports\Importer logic MUST:
>    * A) Look up the course's base_fee from the courses table.
>    * B) Check the assigned branch's database record to see if is_luxury_branch is true, and apply the dynamic markup_percentage if so.
>    * C) Subtract the CSV's "Amount Paid" from this newly calculated total fee to determine the balance_owed.
>    * D) Save this balance_owed to the database so the student's dashboard accurately shows their remaining debt.
> Please write out the Migration, the Filament BranchResource form fields, the Model accessors, and the exact code block for the CSV Importer's resolveRecord() or import() method.
> 
Are you currently using Spatie Permissions or Filament Shield to restrict which admins are actually allowed to toggle these pricing rules, or do all admins have the same access level right now? 

check