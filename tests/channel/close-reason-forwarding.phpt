--TEST--
Channel forwards close reason to receiver.
--SKIPIF--
<?php require __DIR__ . '/skipif.inc'; ?>
--FILE--
<?php

namespace Concurrent;

$channel = new Channel();
$channel->close();

try {
    $channel->send('X');
} catch (ChannelClosedException $e) {
    var_dump($e->getMessage());
    var_dump($e->getPrevious());
}

$channel = new Channel();
$channel->close(new \Error('FOO!'));

try {
    var_dump(iterator_to_array($channel->getIterator()));
} catch (ChannelClosedException $e) {
    var_dump($e->getMessage());
    var_dump($e->getPrevious()->getMessage());
}

--EXPECT--
string(23) "Channel has been closed"
NULL
string(23) "Channel has been closed"
string(4) "FOO!"
