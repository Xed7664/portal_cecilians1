<!DOCTYPE html>
<html>
<head>
    <title>Pre-Enrollment Form</title>
    <style>
        /* General Layout and Font */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Header Table Layout */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            text-align: center;
        }

        /* Requirements Section */
        .requirements {
            border: 1px dashed black;
            padding: 10px;
            width: 150px;
            height: 100px;
            vertical-align: top;
            text-align: left;
        }

        /* School Info Section */
        .school-info-container {
            width: 100%;
            text-align: center;
        }

        .school-info-inner-table {
            margin: 0 auto;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .school-details p {
            margin: 4px 0;
        }

        .school-details p:first-child,
        .school-details p:nth-child(2) {
            font-size: 14px;
            font-weight: bold;
        }

        .school-details p:nth-child(3),
        .school-details p:nth-child(4) {
            font-size: 12px;
            font-weight: bold;
            padding-top: 4px;
        }

        /* Info Table Section */
        .info-table {
            border: 1px solid black;
            width: 220px;
            height: 100px;
            vertical-align: top;
        }

        .info-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid black;
            height: 20px;
            padding: 2px;
        }

        /* Column Widths */
        .thin-column {
            width: 30%;
        }

        .wide-column {
            width: 70%;
        }
    </style>
</head>
<body>

    <!-- Header Section as a Table for PDF Compatibility -->
    <table class="header-table">
        <tr>
            <!-- Requirements Section -->
            <td class="requirements">
                <p>&#x25EF; Birth Certificate</p>
                <p>&#x25EF; 1 by 1 Picture</p>
            </td>

            <!-- School Info with Logo and Details -->
            <td class="school-info-container">
                <table class="school-info-inner-table">
                    <tr>
                        <td><img src="img/scclogo.png" alt="SCC Logo" class="logo"></td>
                        <td class="school-details">
                            <p><strong>St. Cecilia's College-Cebu, Inc.</strong></p>
                            <p>De La Salle Supervised School</p>
                            <p><strong>HIGHER EDUCATION DEPARTMENT</strong></p>
                            <p><strong>REGISTRATION FORM</strong></p>
                            <p><u>{{ $semester }}</u> Semester/Term</p>
                            <p>AY <u>{{ $schoolYear }}</u></p>
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Info Table Section -->
            <td class="info-table">
                <table>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                    <tr>
                        <td class="thin-column"></td>
                        <td class="wide-column"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
