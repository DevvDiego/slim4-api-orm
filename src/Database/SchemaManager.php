<?php
namespace App\Database;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Database\Schemas\UserSchema;
use App\Database\Schemas\CustomerSchema;
use App\Database\Schemas\TicketSchema;
use App\Traits\ResponseTrait;

class SchemaManager {
    use ResponseTrait;
    protected $db;

    public function __construct(Capsule $db) {
        $this->db = $db;
    }

    public function sync(Request $request, Response $response, array $args): Response {
        $this->db::schema()->disableForeignKeyConstraints();
        $createdTables = [];

        try {
            
            $tables = [
                'users'=> UserSchema::class,
                'customers' => CustomerSchema::class,
                'tickets'   => TicketSchema::class,
            ];

            foreach ($tables as $name => $class) {
                if ( !$this->db::schema()->hasTable($name) ) {
                    $class::create();
                    $createdTables[$name] = $name;
                }
            }

            // If there were any evolutions, update the tables then
            $this->updateTables();

            return $this->success($response, "The following tables were created: " . implode(",", $createdTables) );

        } catch (\Exception $e) {
            return $this->error($response, "Error creating tables: " . $e->getMessage(), 500);
            
        } finally {
            //this always executes even after the return inside try or catch
            $this->db::schema()->enableForeignKeyConstraints();
        }
    }

    /**
     * Any new fields must be added here to keep track of them more easily
     */
    private function updateTables() {
        
        /* $this->alter('users', 'phone', function($table) {
            $table->string('phone', 20)->nullable();
        });

        $this->alter('customers', 'address', function($table) {
            $table->text('address')->nullable();
        }); */

    }

    /**
     * Checks if table and row exists before applying other methods on it
     */
    private function alter(string $tableName, string $columnName, callable $callback): bool {

        if ($this->db::schema()->hasTable($tableName) && 
            !$this->db::schema()->hasColumn($tableName, $columnName)) 
        {
            // give control to the callback
            $this->db::schema()->table($tableName, function ($table) use ($callback) {
                $callback($table);
            });
            
            return true;
        }

        return false;
    }
}

?>