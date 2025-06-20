pipeline {
    agent any

    environment {
        PROJECT_NAME = "signup-ci"
    }

    stages {
        stage('Clone Repository') {
            steps {
                git 'https://github.com/malghalara/signup.git'
            }
        }

        stage('Build with Docker Compose') {
            steps {
                script {
                    sh '''
                        echo "🔄 Stopping old containers..."
                        docker-compose -p $PROJECT_NAME -f docker-compose.yml down || true

                        echo "🐳 Building and starting new containers..."
                        docker-compose -p $PROJECT_NAME -f docker-compose.yml up -d --build
                    '''
                }
            }
        }
    }

    post {
        always {
            echo '✅ Pipeline finished!'
        }
    }
}












