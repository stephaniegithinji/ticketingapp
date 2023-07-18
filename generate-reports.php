<?php

require 'vendor/autoload.php';
require_once 'connect/config.php';
require_once 'utils.php';

class Exporter extends Db
{

    public static function logger(string $msg): void
    {
        $log = sprintf('[%s] [%s:%s] [%s] %s', date('D M d H:i:s', $time = microtime(true)) . sprintf('.%06d', ($time - floor($time)) * 1000000) . ' ' . date('Y', $time), 'php', 'warn', 'pid ' . getmypid(), $msg);
        error_log($log);
        file_put_contents('pdf_logs.txt', $log . PHP_EOL, FILE_APPEND);
    }

    // This report provides information about the number of tickets sold and the total amount earned for each event. 
    // It can help assess the popularity and financial success of each event. 
    public function generateEventAttendanceReportPDF()
    {
        try {

            // Create an instance of mPDF
            $mpdf = new \Mpdf\Mpdf();

            // Query the database
            $sql = "SELECT events.id AS event_id, events.event_name, events.date, events.venue, COUNT(reservations.id) AS num_tickets_sold, SUM(reservations.total_amount) AS total_amount_earned
                    FROM events
                    LEFT JOIN reservations ON events.id = reservations.events_id
                    GROUP BY events.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Create HTML table structure
            $html = '<table>';
            $html .= '<tr>
                        <th>Event ID</th>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Number of Tickets Sold</th>
                        <th>Total Amount Earned</th>
                     </tr>';

            // Populate the HTML table
            foreach ($results as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['event_id'] . '</td>';
                $html .= '<td>' . $row['event_name'] . '</td>';
                $html .= '<td>' . $row['date'] . '</td>';
                $html .= '<td>' . $row['venue'] . '</td>';
                $html .= '<td>' . $row['num_tickets_sold'] . '</td>';
                $html .= '<td>' . $row['total_amount_earned'] . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table>';


            $fileName = 'event_attendance_report.pdf';

            $mpdf->SetTitle('Event Attendance Report');

            // Write HTML content to PDF
            $mpdf->WriteHTML($html);

            // Output the PDF
            $mpdf->Output($fileName, 'D');
        } catch (Exception $e) {
            // Catching errors
            self::logger('PDF could not be created. Error: ' . $e->getMessage());
            throw new Exception('PDF could not be created. Error: ' . $e->getMessage());
        }
    }
}
