pipeline {
    agent any
    stages {
        stage('Build') {
            steps {
                sh 'echo "Hello World"'
                // sh 'php --version'
                echo "工作目录：${WORKSPACE}"
                sh '''
                    echo "Multiline shell steps works too"
                    ls -lah
                '''
            }
        }

        stage('test'){
             steps {
                 echo 'test';
             }
        }

        stage('deploy'){
            steps {
             echo 'deploy';
            }
        }
    }
}