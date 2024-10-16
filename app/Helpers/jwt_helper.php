use Firebase\Jwt\JWT;

function getJWT($email,role){
    $key = getenv('JWT_SECRET');

    $payload=[
        'email' => $email, 
        'role' => $role,    
        'iat' => time(),   
        'exp' => time() + 3600];

    return JWT::encode($payload,$key,'HS256');
}

function validateJWT($token){
    $key = getenv('JWT_SECRET');
    try{
        JWT::decode($token, $key, ['HS256']);
    }catch(Exception $e){
        return null;
    }
}