/**
 * Script pour exécuter le schéma SQL sur Railway MySQL
 * Usage: railway run node run-schema.js
 */

const fs = require('fs');
const path = require('path');

// Railway fournit MYSQL_URL ou MYSQLHOST, MYSQLPORT, etc.
const mysqlUrl = process.env.MYSQL_URL;
const mysqlHost = process.env.MYSQLHOST || process.env.MYSQL_HOST;
const mysqlPort = process.env.MYSQLPORT || process.env.MYSQL_PORT || 3306;
const mysqlUser = process.env.MYSQLUSER || process.env.MYSQL_USER || 'root';
const mysqlPassword = process.env.MYSQLPASSWORD || process.env.MYSQL_PASSWORD || '';
const mysqlDatabase = process.env.MYSQLDATABASE || process.env.MYSQL_DATABASE || 'railway';

async function run() {
  let mysql;
  try {
    mysql = require('mysql2/promise');
  } catch (e) {
    console.log('Installation de mysql2...');
    require('child_process').execSync('npm install mysql2', { stdio: 'inherit' });
    mysql = require('mysql2/promise');
  }

  let connection;
  if (mysqlUrl) {
    connection = await mysql.createConnection(mysqlUrl);
  } else if (mysqlHost) {
    connection = await mysql.createConnection({
      host: mysqlHost,
      port: mysqlPort,
      user: mysqlUser,
      password: mysqlPassword,
      multipleStatements: true
    });
  } else {
    console.error('Aucune variable MySQL trouvée. Assure-toi que railway link pointe vers le service MySQL.');
    process.exit(1);
  }

  const schemaPath = path.join(__dirname, 'database', 'schema-railway.sql');
  const sql = fs.readFileSync(schemaPath, 'utf8');

  // Exécuter chaque instruction séparément (évite les erreurs multi-statements)
  const statements = sql
    .split(';')
    .map(s => s.trim())
    .filter(s => s.length > 0 && !s.startsWith('--'));

  console.log('Exécution du schéma...');
  for (const stmt of statements) {
    if (stmt.trim()) await connection.query(stmt + ';');
  }
  console.log('Schéma exécuté avec succès !');
  await connection.end();
  process.exit(0);
}

run().catch(err => {
  console.error('Erreur:', err.message);
  process.exit(1);
});
