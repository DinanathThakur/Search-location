<?php

class Table extends DB
{

    private $tableName = null;

    public function __construct($tableName)
    {
        parent::__construct();
        $this->tableName = $tableName;
    }

    public function insert($data)
    {
        try {
            $insertToColumns = '`' . implode('`, `', array_keys($data)) . '`';
            $values = '"' . implode('", "', $data) . '"';

            $insertQuery = "INSERT INTO $this->tableName ($insertToColumns) VALUES ($values);";

            $result = mysqli_query($this->connection, $insertQuery);

            if ($result) {
                $response = ['status' => 'success', 'msg' => 'New record created successfully.', 'data' => mysqli_insert_id($this->connection)];
            } else {
                $response = ['status' => 'error', 'msg' => "Error creating record: " . mysqli_error($this->connection)];
            }
        } catch (Exception $exc) {
            $response = ['status' => 'error', 'msg' => "Something went wrong: " . $exc->getTraceAsString()];
        }
        return $response;
    }

    private function generateSelectQuery($options = null)
    {

        $columns = isset($options['selectColumns']) ? implode(',', $options['selectColumns']) : ' * ';
        $sql = "SELECT $columns FROM $this->tableName";

        if (!empty($options)) {

            $where = 1;
            if (isset($options['where'])) {
                $where = $options['where'];
            }
            $sql .= " WHERE $where ";

            if (isset($options['orderBy'])) {
                $orderBy = $options['orderBy'];
                $sql .= " ORDER BY $orderBy ";
            }

            if (isset($options['limit'])) {
                $limit = (int) $options['limit'];
                $offset = (int) (isset($options['offset']) ? $options['offset'] : 0);
                $sql .= " LIMIT $offset,$limit ";
            }
        }

        return $sql;
    }

    public function get($options = null)
    {
        try {
            $sql = $this->generateSelectQuery($options);

            $result = mysqli_query($this->connection, $sql);
            $data = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            $response = ['status' => 'success', 'msg' => 'Record fetched successfully.', 'data' => $data];
        } catch (Exception $exc) {
            $response = ['status' => 'error', 'msg' => "Something went wrong: " . $exc->getTraceAsString()];
        }
        return $response;
    }

    public function getCount($where = 1)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM $this->tableName WHERE $where";
            $result = mysqli_query($this->connection, $sql);
            $data = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            $response = ['status' => 'success', 'msg' => 'Record fetched successfully.', 'data' => $data['count']];
        } catch (Exception $exc) {
            $response = ['status' => 'error', 'msg' => "Something went wrong: " . $exc->getTraceAsString()];
        }
        return $response;
    }

    public function getAll($options = null)
    {
        try {
            $sql = $this->generateSelectQuery($options);

            $result = mysqli_query($this->connection, $sql);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            mysqli_free_result($result);

            $response = ['status' => 'success', 'msg' => 'Records fetched successfully.', 'data' => $data];
        } catch (Exception $exc) {
            $response = ['status' => 'error', 'msg' => "Something went wrong: " . $exc->getTraceAsString()];
        }
        return $response;
    }

    public function update($data, $where)
    {
        try {
            $set = implode(', ', array_filter(array_map(function ($column) use ($data) {
                return "$column = '$data[$column]'";
            }, array_keys($data))));

            $query = "UPDATE $this->tableName SET $set WHERE $where";

            $updateResult = mysqli_query($this->connection, $query);

            if ($updateResult) {
                $response = ['status' => 'success', 'msg' => 'Record has been updated successfully.'];
            } else {
                $response = ['status' => 'error', 'msg' => "Error updating record: " . mysqli_error($this->connection)];
            }
        } catch (Exception $exc) {
            $response = ['status' => 'error', 'msg' => "Something went wrong: " . $exc->getTraceAsString()];
        }
        return $response;
    }

    public function delete($where)
    {
        try {
            $query = "DELETE FROM $this->tableName WHERE $where";
            $result = mysqli_query($this->connection, $query);
            $deleteResult = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            if ($deleteResult) {
                $response = ['status' => 'success', 'msg' => 'Record has been deleted successfully.'];
            } else {
                $response = ['status' => 'error', 'msg' => "Error deleting record: " . mysqli_error($this->connection)];
            }
        } catch (Exception $exc) {
            $response = ['status' => 'error', 'msg' => "Something went wrong: " . $exc->getTraceAsString()];
        }
        return $response;
    }

}
