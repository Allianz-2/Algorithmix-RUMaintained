<?php
    require_once '../../vendor/autoload.php';
    $newResponse = false;

    use ArdaGnsrn\Ollama\Ollama;
    use ArdaGnsrn\Ollama\Resources\Blobs;
    use ArdaGnsrn\Ollama\Resources\Chat;
    use ArdaGnsrn\Ollama\Resources\Completions;
    use ArdaGnsrn\Ollama\Resources\Embed;
    use ArdaGnsrn\Ollama\Resources\Models;

if (isset($_POST['submit-message'])) {
    unset($_POST['submit-message']);
    $client = \ArdaGnsrn\Ollama\Ollama::client('http://localhost:11434');

    if (!isset($_SESSION['Firstname'])) {
        $userName = 'User'; // Initialize the user's name
    } else {
        $userName = $_SESSION['Firstname']; // Get the user's name and make it bold
    }

    $message = $userName . ":: " . $_POST['user-input'] ."\n"; // Get the user's message 
    $context = "Instructions:' 

                1. You are an AI assisstant for RUMaintained. 
                2. Based on user input, answer all questions. 
                3. If you do not understand the input, or require additional information, direct the user to “RU.Maintained@gmail.com”
                4. Limit all responses to 100 words or less' 

                Context: 
                    '**Project Objectives**

                    The primary objective of the Residence Maintenance System project is to develop a robust web-based platform aimed at streamlining the reporting and tracking of maintenance issues within Rhodes University Residences. This system will serve as a centralized hub for students, house wardens, hall secretaries, and maintenance staff to communicate and collaborate efficiently on maintenance tasks.

                    ### Key Objectives:

                    1. **User-Friendly Reporting:**
                        - Enable students to easily report maintenance faults within their residences.
                        - Allow students to create accounts, register their residences, and submit detailed reports on maintenance issues.
                        - Facilitate the uploading of photos related to reported faults, providing maintenance staff with valuable visual information for assessment and resolution.
                    2. **House Warden Engagement:**
                        - Empower house wardens to confirm reported faults and add comments as necessary, ensuring accurate communication and effective management of maintenance tasks.
                        - Grant house wardens the authority to close erroneous tickets, maintaining system integrity and reducing unnecessary workload for maintenance staff.
                    3. **Hall Secretary Oversight:**
                        - Equip hall secretaries to oversee the requisition process for maintenance tasks, including assigning tasks to maintenance staff and monitoring the progress of fault resolution.
                        - Provide hall secretaries with access to comprehensive reports on maintenance fault statistics, enabling data-driven decision-making and identification of areas for improvement in maintenance management practices.
                    4. **Maintenance Staff Efficiency:**
                        - Create a dedicated platform for maintenance staff to view and respond to assigned tasks promptly.
                        - Streamline the maintenance process to facilitate timely resolution of reported faults, thereby improving the overall living experience for students in Rhodes University Residences.

                    ---

                    **Prototype 1: Maintenance Ticket Creation Overview**

                    **Purpose**
                    The *Maintenance Ticket Creation* window in RUMaintained allows Rhodes University users to report maintenance issues within their residences. This interface facilitates easy submission of fault details, ensuring efficient processing and follow-up by the maintenance team.

                    **User Interaction**
                    After logging in, users navigate to the *Services* tab, selecting *Create Your Maintenance Ticket*. The ticket creation form includes a progress bar showing the steps for ticket approval by the house warden and hall secretary, maintaining visibility of system status. The form itself is divided into *Ticket Details* and *Additional Information* sections, with fields for description, category, priority, and location, using input controls like text boxes, drop-downs, and radio buttons. Users can save the form to complete later or directly submit it for approval. A help feature allows users to access AI-based assistance if needed.

                    **Defaults**
                    The form uses placeholder text, like “e.g., Broken tap faucet” in the description box, to guide users on the required information. The *Create Ticket* button is prominently highlighted for easy access after form completion.

                    **Dependencies and Design**
                    The banner and form elements respond to user actions, such as underlining when hovered over, to show visibility of system status. Drop-down options expand on click, and a progress bar maintains user awareness of each step in the process. Minimalistic design elements, with ample white space, clear labels, and contrasting text, enhance readability.

                    **Validation**
                    Required fields are marked with red asterisks, and error messages prompt users to fill in missing information. Users can save incomplete tickets, but final submission is only possible if all required fields are completed. Optional sections like *Additional Information* allow flexibility in adding further details without affecting ticket creation.

                    ---
                    **Prototype 2: Hall Secretary Dashboard Overview**
                    **Purpose**
                    The Hall Secretary Dashboard allows Hall Secretaries to manage and finalize maintenance tickets confirmed by House Wardens. It serves as a centralized platform for confirming tickets, requisitioning maintenance staff, and monitoring analytics on maintenance requests across residences.
                    **User Interaction**
                    The dashboard includes an expandable menu accessible via a burger icon, offering options to switch views, access settings, or log out. Key features include analytics views (e.g., graphs) that can be customized by date, residence, severity, category, and status, ensuring user control and flexibility. A table displays pending and active tickets for easy management of ongoing maintenance needs.
                    **Defaults**
                    By default, the dashboard highlights analytics for the residence with the most high-severity tickets from the past seven days, prioritizing urgent issues. Users can customize views via drop-down menus for broader insights.
                    **Dependencies and Design**
                    The dashboard's burger menu maintains a minimalist interface, reducing clutter and adhering to Nielsen’s heuristics, such as *Recognition rather than Recall* and *Aesthetic and Minimalist Design*. Hover or click effects highlight selected menu items, ensuring visibility of the system status.
                    **Validation**
                    As the dashboard only supports viewing and managing data, no validation is required for new data entries.
                    ---
                    **RUMaintained Overview:**
                    RUMaintained is a residence maintenance system at Rhodes University designed to streamline the reporting and tracking of maintenance issues within university residences. Its mission is to create a well-maintained living environment that supports student success by facilitating easy communication between students and maintenance teams.
                    **Key Features:**
                    - **User-Friendly:** Simple interface for students to report and track maintenance issues.
                    - **Transparency:** Maintenance process is fully documented for all relevant parties, ensuring accountability.
                    - **Efficiency:** Connects students, house wardens, hall secretaries, and maintenance staff for timely repairs.

                    **FAQs:**
                    - **Purpose:** Efficiently manage maintenance in university residences.
                    - **Reporting Issues:** Log in, go to ' Report Issue', and submit details.
                    - **Tracking Requests:** Status updates are available on the dashboard.
                    - **Types of Issues:** Any maintenance-related problems, such as plumbing or electrical.
                    - **Password Help:** Use 'Forgot Password' on the login page to reset.',

                "; // Define the context


    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = $message ; // Initialize the chat history
    } else {
        $_SESSION['history'] .= $message; // Append the message to the chat history
    }

    $completions = $client->completions()->create([
        'model' => 'llama3.2',
        'prompt' => $context . "History:" . $_SESSION['history'],
    ]);

    $responseArray = $completions->toArray(); // Convert the response to an array
    $response = "Chatbot:: ". $responseArray['response'] . "\n"; // Get the chatbot's response
    $_SESSION['history'] .= $response; // Append the message and response to the chat history 
    $newResponse = true;
}

?>
