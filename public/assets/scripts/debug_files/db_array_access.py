import pymysql

def connect_to_database(host, user, password, db):
    """Establish a connection to the MySQL database."""
    try:
        connection = pymysql.connect(
            host=host,
            user=user,
            password=password,
            database=db
        )
        print("Connection to database established successfully.")
        return connection
    except pymysql.MySQLError as e:
        print(f"Error connecting to database: {e}")
        return None
    

def fetch_data(connection, query):
    """Fetch data from the database using the provided query."""
    try:
        with connection.cursor() as cursor:
            cursor.execute(query)
            results = cursor.fetchall()
            return results
    except pymysql.MySQLError as e:
        print(f"Error fetching data: {e}")
        return None
    

def close_connection(connection):
    """Close the database connection."""
    try:
        connection.close()
        print("Database connection closed.")
    except pymysql.MySQLError as e:
        print(f"Error closing connection: {e}")


# Example usage
if __name__ == "__main__":
    db_connection = connect_to_database('127.0.0.1', 'root', '', 'firststep')
    if db_connection:
        # data = fetch_data(db_connection, "SELECT * FROM exam_jobs")
        # print("Fetched data:", data)
        # close_connection(db_connection)

        cursor = db_connection.cursor()
        # cursor.execute('SELECT * FROM exam_jobs')
        # rows = cursor.fetchall()
        # for row in rows:
        #     print(row)
        
        # close_connection(db_connection)


        cursor.execute("SELECT payload FROM exam_jobs WHERE id = %s", (35,))
        row = cursor.fetchone()

        if not row or not row[0]:
            raise Exception("Payload not found")
        
        # payload_ = row['payload'] --- not working with this code
        payload_ = row[0]
        print("Payload:", payload_)
        close_connection(db_connection)