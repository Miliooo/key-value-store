<?php

/*
 * This file is part of the webmozart/key-value-store package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\KeyValueStore\Tests;

use Basho\Riak\Riak;
use Webmozart\KeyValueStore\RiakStore;
use Webmozart\KeyValueStore\Tests\Fixtures\TestException;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RiakStoreTest extends AbstractKeyValueStoreTest
{
    private static $supported;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $client = new Riak();

        self::$supported = $client->isAlive();
    }

    protected function setUp()
    {
        if (!self::$supported) {
            $this->markTestSkipped('Riak is not running.');
        }

        parent::setUp();
    }

    protected function createStore()
    {
        return new RiakStore('test-bucket');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\WriteException
     * @expectedExceptionMessage I failed!
     */
    public function testSetThrowsWriteExceptionIfWriteFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->set('key', 'value');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\WriteException
     * @expectedExceptionMessage I failed!
     */
    public function testRemoveThrowsWriteExceptionIfWriteFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->remove('key');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\WriteException
     * @expectedExceptionMessage I failed!
     */
    public function testRemoveThrowsWriteExceptionIfExistsFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();
        $bucket = $this->getMockBuilder('Basho\Riak\Bucket')
            ->disableOriginalConstructor()
            ->getMock();
        $object = $this->getMockBuilder('Basho\Riak\Object')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willReturn($bucket);

        $bucket->expects($this->once())
            ->method('get')
            ->willReturn($object);

        $object->expects($this->once())
            ->method('exists')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->remove('key');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\WriteException
     * @expectedExceptionMessage I failed!
     */
    public function testRemoveThrowsWriteExceptionIfDeleteFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();
        $bucket = $this->getMockBuilder('Basho\Riak\Bucket')
            ->disableOriginalConstructor()
            ->getMock();
        $object = $this->getMockBuilder('Basho\Riak\Object')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willReturn($bucket);

        $bucket->expects($this->once())
            ->method('get')
            ->willReturn($object);

        $object->expects($this->once())
            ->method('exists')
            ->willReturn(true);

        $object->expects($this->once())
            ->method('delete')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->remove('key');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\WriteException
     * @expectedExceptionMessage I failed!
     */
    public function testClearThrowsWriteExceptionIfWriteFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->clear();
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\WriteException
     * @expectedExceptionMessage I failed!
     */
    public function testClearThrowsWriteExceptionIfDeleteFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();
        $bucket = $this->getMockBuilder('Basho\Riak\Bucket')
            ->disableOriginalConstructor()
            ->getMock();
        $object = $this->getMockBuilder('Basho\Riak\Object')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willReturn($bucket);

        $bucket->expects($this->once())
            ->method('getKeys')
            ->willReturn(array('key'));

        $bucket->expects($this->once())
            ->method('get')
            ->willReturn($object);

        $object->expects($this->once())
            ->method('delete')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->clear();
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\ReadException
     * @expectedExceptionMessage I failed!
     */
    public function testGetThrowsReadExceptionIfReadFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->get('key');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\ReadException
     * @expectedExceptionMessage I failed!
     */
    public function testGetThrowsReadExceptionIfExistsFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();
        $bucket = $this->getMockBuilder('Basho\Riak\Bucket')
            ->disableOriginalConstructor()
            ->getMock();
        $object = $this->getMockBuilder('Basho\Riak\Object')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willReturn($bucket);

        $bucket->expects($this->once())
            ->method('getBinary')
            ->willReturn($object);

        $object->expects($this->once())
            ->method('exists')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->get('key');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\UnserializationFailedException
     */
    public function testGetThrowsExceptionIfNotUnserializable()
    {
        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();
        $bucket = $this->getMockBuilder('Basho\Riak\Bucket')
            ->disableOriginalConstructor()
            ->getMock();
        $object = $this->getMockBuilder('Basho\Riak\Object')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willReturn($bucket);

        $bucket->expects($this->once())
            ->method('getBinary')
            ->willReturn($object);

        $object->expects($this->once())
            ->method('exists')
            ->willReturn(true);

        $object->expects($this->once())
            ->method('getData')
            ->willReturn('foobar');

        $store = new RiakStore('test-bucket', $client);
        $store->get('key');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\ReadException
     * @expectedExceptionMessage I failed!
     */
    public function testHasThrowsReadExceptionIfReadFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->has('key');
    }

    /**
     * @expectedException \Webmozart\KeyValueStore\Api\ReadException
     * @expectedExceptionMessage I failed!
     */
    public function testHasThrowsReadExceptionIfExistsFails()
    {
        $exception = new TestException('I failed!');

        $client = $this->getMockBuilder('Basho\Riak\Riak')
            ->disableOriginalConstructor()
            ->getMock();
        $bucket = $this->getMockBuilder('Basho\Riak\Bucket')
            ->disableOriginalConstructor()
            ->getMock();
        $object = $this->getMockBuilder('Basho\Riak\Object')
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('bucket')
            ->willReturn($bucket);

        $bucket->expects($this->once())
            ->method('get')
            ->willReturn($object);

        $object->expects($this->once())
            ->method('exists')
            ->willThrowException($exception);

        $store = new RiakStore('test-bucket', $client);
        $store->has('key');
    }
}
