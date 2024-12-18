<!--date=20241203 -->

<?php include("../../headercat.php"); ?>

<h1>Creating a Simple Client-Server Application in C</h1>
<p>In this tutorial, we will create a simple client-server application in C. The server will listen for incoming connections on a specified port, and the client will connect to the server, send a message, and receive a response. This tutorial is aimed at beginners, so we will explain each line of code in detail.</p>

<h2>Server Code</h2>
<pre>
<code class="language-c">
// server.c
#include &lt;stdio.h&gt;
#include &lt;stdlib.h&gt;
#include &lt;string.h&gt;
#include &lt;unistd.h&gt;
#include &lt;arpa/inet.h&gt;

#define PORT 8080

int main() {
    int server_fd, new_socket;
    struct sockaddr_in address;
    int opt = 1;
    int addrlen = sizeof(address);
    char buffer[1024] = {0};
    const char *hello = "Hello from server";

    // Creating socket file descriptor
    if ((server_fd = socket(AF_INET, SOCK_STREAM, 0)) == 0) {
        perror("socket failed");
        exit(EXIT_FAILURE);
    }

    // Forcefully attaching socket to the port 8080
    if (setsockopt(server_fd, SOL_SOCKET, SO_REUSEADDR | SO_REUSEPORT, &opt, sizeof(opt))) {
        perror("setsockopt");
        close(server_fd);
        exit(EXIT_FAILURE);
    }

    address.sin_family = AF_INET;
    address.sin_addr.s_addr = INADDR_ANY;
    address.sin_port = htons(PORT);

    // Forcefully attaching socket to the port 8080
    if (bind(server_fd, (struct sockaddr *)&address, sizeof(address)) < 0) {
        perror("bind failed");
        close(server_fd);
        exit(EXIT_FAILURE);
    }

    if (listen(server_fd, 3) < 0) {
        perror("listen");
        close(server_fd);
        exit(EXIT_FAILURE);
    }

    if ((new_socket = accept(server_fd, (struct sockaddr *)&address, (socklen_t*)&addrlen)) < 0) {
        perror("accept");
        close(server_fd);
        exit(EXIT_FAILURE);
    }

    read(new_socket, buffer, 1024);
    printf("Message from client: %s\n", buffer);
    send(new_socket, hello, strlen(hello), 0);
    printf("Hello message sent\n");

    close(new_socket);
    close(server_fd);
    return 0;
}
</code>
</pre>

<p>Let's break down the server code:</p>
<ul>
    <li><code>#include &lt;stdio.h&gt;</code>: Includes the standard input/output library.</li>
    <li><code>#include &lt;stdlib.h&gt;</code>: Includes the standard library for memory allocation, process control, conversions, etc.</li>
    <li><code>#include &lt;string.h&gt;</code>: Includes the string handling library.</li>
    <li><code>#include &lt;unistd.h&gt;</code>: Includes the POSIX operating system API.</li>
    <li><code>#include &lt;arpa/inet.h&gt;</code>: Includes definitions for internet operations.</li>
    <li><code>#define PORT 8080</code>: Defines the port number the server will listen on.</li>
    <li><code>int main()</code>: The main function where the program execution begins.</li>
    <li><code>int server_fd, new_socket;</code>: Declares file descriptors for the server and new socket.</li>
    <li><code>struct sockaddr_in address;</code>: Declares a structure to hold the server address.</li>
    <li><code>int opt = 1;</code>: Option for setsockopt to reuse the address and port.</li>
    <li><code>int addrlen = sizeof(address);</code>: Length of the address structure.</li>
    <li><code>char buffer[1024] = {0};</code>: Buffer to store the message from the client.</li>
    <li><code>const char *hello = "Hello from server";</code>: Message to send to the client.</li>
    <li><code>if ((server_fd = socket(AF_INET, SOCK_STREAM, 0)) == 0)</code>: Creates a socket and checks for errors.</li>
    <li><code>if (setsockopt(server_fd, SOL_SOCKET, SO_REUSEADDR | SO_REUSEPORT, &opt, sizeof(opt)))</code>: Sets socket options to reuse the address and port.</li>
    <li><code>address.sin_family = AF_INET;</code>: Sets the address family to IPv4.</li>
    <li><code>address.sin_addr.s_addr = INADDR_ANY;</code>: Binds the socket to all available interfaces.</li>
    <li><code>address.sin_port = htons(PORT);</code>: Sets the port number, converting it to network byte order.</li>
    <li><code>if (bind(server_fd, (struct sockaddr *)&address, sizeof(address)) < 0)</code>: Binds the socket to the specified address and port.</li>
    <li><code>if (listen(server_fd, 3) < 0)</code>: Listens for incoming connections with a backlog of 3.</li>
    <li><code>if ((new_socket = accept(server_fd, (struct sockaddr *)&address, (socklen_t*)&addrlen)) < 0)</code>: Accepts an incoming connection.</li>
    <li><code>read(new_socket, buffer, 1024);</code>: Reads the message from the client into the buffer.</li>
    <li><code>printf("Message from client: %s\n", buffer);</code>: Prints the message from the client.</li>
    <li><code>send(new_socket, hello, strlen(hello), 0);</code>: Sends the hello message to the client.</li>
    <li><code>printf("Hello message sent\n");</code>: Prints a confirmation message.</li>
    <li><code>close(new_socket);</code>: Closes the new socket.</li>
    <li><code>close(server_fd);</code>: Closes the server socket.</li>
    <li><code>return 0;</code>: Exits the program.</li>
</ul>

<h2>Client Code</h2>
<pre>
<code class="language-c">
#include &lt;stdio.h&gt;
#include &lt;stdlib.h&gt;
#include &lt;string.h&gt;
#include &lt;unistd.h&gt;
#include &lt;arpa/inet.h&gt;

#define PORT 8080

int main() {
    int sock = 0, valread;
    struct sockaddr_in serv_addr;
    char *hello = "Hello from client";
    char buffer[1024] = {0};

    if ((sock = socket(AF_INET, SOCK_STREAM, 0)) < 0) {
        printf("\n Socket creation error \n");
        return -1;
    }

    serv_addr.sin_family = AF_INET;
    serv_addr.sin_port = htons(PORT);

    // Convert IPv4 and IPv6 addresses from text to binary form
    if (inet_pton(AF_INET, "127.0.0.1", &serv_addr.sin_addr) <= 0) {
        printf("\nInvalid address/ Address not supported \n");
        return -1;
    }

    if (connect(sock, (struct sockaddr *)&serv_addr, sizeof(serv_addr)) < 0) {
        printf("\nConnection Failed \n");
        return -1;
    }

    send(sock, hello, strlen(hello), 0);
    printf("Hello message sent\n");
    valread = read(sock, buffer, 1024);
    printf("%s\n", buffer);

    return 0;
}
</code>
</pre>

<p>Let's break down the client code:</p>
<ul>
    <li><code>#include &lt;stdio.h&gt;</code>: Includes the standard input/output library.</li>
    <li><code>#include &lt;stdlib.h&gt;</code>: Includes the standard library for memory allocation, process control, conversions, etc.</li>
    <li><code>#include &lt;string.h&gt;</code>: Includes the string handling library.</li>
    <li><code>#include &lt;unistd.h&gt;</code>: Includes the POSIX operating system API.</li>
    <li><code>#include &lt;arpa/inet.h&gt;</code>: Includes definitions for internet operations.</li>
    <li><code>#define PORT 8080</code>: Defines the port number the client will connect to.</li>
    <li><code>int main()</code>: The main function where the program execution begins.</li>
    <li><code>int sock = 0, valread;</code>: Declares a socket and a variable to store the number of bytes read.</li>
    <li><code>struct sockaddr_in serv_addr;</code>: Declares a structure to hold the server address.</li>
    <li><code>char *hello = "Hello from client";</code>: Message to send to the server.</li>
    <li><code>char buffer[1024] = {0};</code>: Buffer to store the message from the server.</li>
    <li><code>if ((sock = socket(AF_INET, SOCK_STREAM, 0)) < 0)</code>: Creates a socket and checks for errors.</li>
    <li><code>serv_addr.sin_family = AF_INET;</code>: Sets the address family to IPv4.</li>
    <li><code>serv_addr.sin_port = htons(PORT);</code>: Sets the port number, converting it to network byte order.</li>
    <li><code>if (inet_pton(AF_INET, "127.0.0.1", &serv_addr.sin_addr) <= 0)</code>: Converts the IP address from text to binary form and checks for errors.</li>
    <li><code>if (connect(sock, (struct sockaddr *)&serv_addr, sizeof(serv_addr)) < 0)</code>: Connects to the server and checks for errors.</li>
    <li><code>send(sock, hello, strlen(hello), 0);</code>: Sends the hello message to the server.</li>
    <li><code>printf("Hello message sent\n");</code>: Prints a confirmation message.</li>
    <li><code>valread = read(sock, buffer, 1024);</code>: Reads the message from the server into the buffer.</li>
    <li><code>printf("%s\n", buffer);</code>: Prints the message from the server.</li>
    <li><code>return 0;</code>: Exits the program.</li>
</ul>

<h2>Compiling and Running the Code</h2>
<p>To compile and run the provided C source code files for the ARM64 architecture, follow these steps:</p>
<pre>
<code class="language-sh">
# Compile the server code
gcc -o server server.c

# Compile the client code
gcc -o client client.c

# Run the server
./server

# Run the client in a separate terminal
./client
</code>
</pre>

<p>This will start the server, which listens on port 8080, and the client will connect to the server, send a message, and receive a response.</p>

<p>&nbsp;</p>
<?php include("../../footer.php"); ?>
