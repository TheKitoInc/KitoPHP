<?php

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testKitoCryptographyException()
    {
        $this->expectException(Kito\Cryptography\Exception::class);

        throw new Kito\Cryptography\Exception('Test');
    }

    public function testKitoCryptographyHashAlgorithmCalcException()
    {
        $this->expectException(Kito\Cryptography\HashAlgorithmCalcException::class);

        throw new Kito\Cryptography\HashAlgorithmCalcException('Test');
    }

    public function testKitoCryptographyHashAlgorithmNotFoundException()
    {
        $this->expectException(Kito\Cryptography\HashAlgorithmNotFoundException::class);

        throw new Kito\Cryptography\HashAlgorithmNotFoundException('Test');
    }

    public function testKitoCryptographyInvalidHashValueException()
    {
        $this->expectException(Kito\Cryptography\InvalidHashValueException::class);

        throw new Kito\Cryptography\InvalidHashValueException('Test');
    }

    public function testKitoException()
    {
        $this->expectException(Kito\Exception::class);

        throw new Kito\Exception('Test');
    }

    public function testKitoExtensionsException()
    {
        $this->expectException(Kito\Extensions\Exception::class);

        throw new Kito\Extensions\Exception('Test');
    }

    public function testKitoFileSystemCopyFileException()
    {
        $this->expectException(Kito\FileSystem\CopyFileException::class);

        throw new Kito\FileSystem\CopyFileException('Test');
    }

    public function testKitoFileSystemCreateDirectoryException()
    {
        $this->expectException(Kito\FileSystem\CreateDirectoryException::class);

        throw new Kito\FileSystem\CreateDirectoryException('Test');
    }

    public function testKitoFileSystemCreateFileException()
    {
        $this->expectException(Kito\FileSystem\CreateFileException::class);

        throw new Kito\FileSystem\CreateFileException('Test');
    }

    public function testKitoFileSystemException()
    {
        $this->expectException(Kito\FileSystem\Exception::class);

        throw new Kito\FileSystem\Exception('Test');
    }

    public function testKitoFileSystemIOException()
    {
        $this->expectException(Kito\FileSystem\IOException::class);

        throw new Kito\FileSystem\IOException('Test');
    }

    public function testKitoFileSystemNotFoundException()
    {
        $this->expectException(Kito\FileSystem\NotFoundException::class);

        throw new Kito\FileSystem\NotFoundException('Test');
    }

    public function testKitoFileSystemNotIsDirectoryException()
    {
        $this->expectException(Kito\FileSystem\NotIsDirectoryException::class);

        throw new Kito\FileSystem\NotIsDirectoryException('Test');
    }

    public function testKitoFileSystemNotIsFileException()
    {
        $this->expectException(Kito\FileSystem\NotIsFileException::class);

        throw new Kito\FileSystem\NotIsFileException('Test');
    }

    public function testKitoFileSystemNotIsLinkException()
    {
        $this->expectException(Kito\FileSystem\NotIsLinkException::class);

        throw new Kito\FileSystem\NotIsLinkException('Test');
    }

    public function testKitoFileSystemNotIsReadableException()
    {
        $this->expectException(Kito\FileSystem\NotIsReadableException::class);

        throw new Kito\FileSystem\NotIsReadableException('Test');
    }

    public function testKitoFileSystemNotIsWritableException()
    {
        $this->expectException(Kito\FileSystem\NotIsWritableException::class);

        throw new Kito\FileSystem\NotIsWritableException('Test');
    }

    public function testKitoHTTPClientException()
    {
        $this->expectException(Kito\HTTP\Client\Exception::class);

        throw new Kito\HTTP\Client\Exception('Test');
    }

    public function testKitoHTTPException()
    {
        $this->expectException(Kito\HTTP\Exception::class);

        throw new Kito\HTTP\Exception('Test');
    }

    public function testKitoHTTPServerException()
    {
        $this->expectException(Kito\HTTP\Server\Exception::class);

        throw new Kito\HTTP\Server\Exception('Test');
    }

    public function testKitoKeyValueException()
    {
        $this->expectException(Kito\KeyValue\Exception::class);

        throw new Kito\KeyValue\Exception('Test');
    }

    public function testKitoLabException()
    {
        $this->expectException(Kito\Lab\Exception::class);

        throw new Kito\Lab\Exception('Test');
    }

    public function testKitoLabHTMLException()
    {
        $this->expectException(Kito\Lab\HTML\Exception::class);

        throw new Kito\Lab\HTML\Exception('Test');
    }

    public function testKitoLabHTMLTagException()
    {
        $this->expectException(Kito\Lab\HTML\Tag\Exception::class);

        throw new Kito\Lab\HTML\Tag\Exception('Test');
    }

    public function testKitoLabHTTPException()
    {
        $this->expectException(Kito\Lab\HTTP\Exception::class);

        throw new Kito\Lab\HTTP\Exception('Test');
    }

    public function testKitoLabHTTPHTTPException()
    {
        $this->expectException(Kito\Lab\HTTP\HTTPException::class);

        throw new Kito\Lab\HTTP\HTTPException('Test');
    }

    public function testKitoLabHTTPSessionException()
    {
        $this->expectException(Kito\Lab\HTTP\Session\Exception::class);

        throw new Kito\Lab\HTTP\Session\Exception('Test');
    }

    public function testKitoLabLegacyException()
    {
        $this->expectException(Kito\Lab\Legacy\Exception::class);

        throw new Kito\Lab\Legacy\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesDataBaseException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\DataBase\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\DataBase\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesDataBaseImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\DataBase\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\DataBase\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesEditorException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Editor\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Editor\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesEditorScriptsException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Editor\Scripts\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Editor\Scripts\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesFormException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Form\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Form\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesFormImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Form\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Form\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesFormScriptsException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Form\Scripts\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Form\Scripts\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesHTMLDefaultTemplateException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\HTML\DefaultTemplate\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\HTML\DefaultTemplate\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesHTMLException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\HTML\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\HTML\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesHTMLImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\HTML\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\HTML\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesHTMLScriptsException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\HTML\Scripts\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\HTML\Scripts\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesIDEException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\IDE\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\IDE\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesIDEImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\IDE\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\IDE\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesLoggerException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Logger\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Logger\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesLoggerImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Logger\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Logger\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesMapException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Map\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Map\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesMapScriptsException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Map\Scripts\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Map\Scripts\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesMySqlException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\MySql\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\MySql\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesMySqlImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\MySql\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\MySql\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesRssException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Rss\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Rss\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesRssImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Rss\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Rss\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesZonesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Zones\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Zones\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkModulesZonesImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\Modules\Zones\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\Modules\Zones\Images\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkSystemException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\System\Exception::class);

        throw new Kito\Lab\Legacy\Framework\System\Exception('Test');
    }

    public function testKitoLabLegacyFrameworkSystemImagesException()
    {
        $this->expectException(Kito\Lab\Legacy\Framework\System\Images\Exception::class);

        throw new Kito\Lab\Legacy\Framework\System\Images\Exception('Test');
    }

    public function testKitoLabLegacyioException()
    {
        $this->expectException(Kito\Lab\Legacy\io\Exception::class);

        throw new Kito\Lab\Legacy\io\Exception('Test');
    }

    public function testKitoLabLegacyionetworkException()
    {
        $this->expectException(Kito\Lab\Legacy\io\network\Exception::class);

        throw new Kito\Lab\Legacy\io\network\Exception('Test');
    }

    public function testKitoLabLegacyionetworkhttpException()
    {
        $this->expectException(Kito\Lab\Legacy\io\network\http\Exception::class);

        throw new Kito\Lab\Legacy\io\network\http\Exception('Test');
    }

    public function testKitoLabLegacystorageException()
    {
        $this->expectException(Kito\Lab\Legacy\storage\Exception::class);

        throw new Kito\Lab\Legacy\storage\Exception('Test');
    }

    public function testKitoLabLegacystoragedbException()
    {
        $this->expectException(Kito\Lab\Legacy\storage\db\Exception::class);

        throw new Kito\Lab\Legacy\storage\db\Exception('Test');
    }

    public function testKitoLabLegacystoragedbrelationalException()
    {
        $this->expectException(Kito\Lab\Legacy\storage\db\relational\Exception::class);

        throw new Kito\Lab\Legacy\storage\db\relational\Exception('Test');
    }

    public function testKitoLabNetworkException()
    {
        $this->expectException(Kito\Lab\Network\Exception::class);

        throw new Kito\Lab\Network\Exception('Test');
    }

    public function testKitoLabStorageDataBaseException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\Exception::class);

        throw new Kito\Lab\Storage\DataBase\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemDataNException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\DataN\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\DataN\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemQueueException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Queue\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Queue\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemQueueKeyValueException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Queue\KeyValue\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Queue\KeyValue\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemTreeCommonException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Tree\Common\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Tree\Common\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemTreeException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Tree\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Tree\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemTreeNodeException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Tree\Node\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Tree\Node\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemTreeStandardAddressException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Tree\Standard\Address\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Tree\Standard\Address\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemTreeStandardException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Tree\Standard\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Tree\Standard\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemTreeStandardMessagesException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Tree\Standard\Messages\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Tree\Standard\Messages\Exception('Test');
    }

    public function testKitoLabStorageDataBaseSubSystemTreeZoneException()
    {
        $this->expectException(Kito\Lab\Storage\DataBase\SubSystem\Tree\Zone\Exception::class);

        throw new Kito\Lab\Storage\DataBase\SubSystem\Tree\Zone\Exception('Test');
    }

    public function testKitoLabStorageException()
    {
        $this->expectException(Kito\Lab\Storage\Exception::class);

        throw new Kito\Lab\Storage\Exception('Test');
    }

    public function testKitoLabStorageFileSystemException()
    {
        $this->expectException(Kito\Lab\Storage\FileSystem\Exception::class);

        throw new Kito\Lab\Storage\FileSystem\Exception('Test');
    }

    public function testKitoLabStorageFileSystemFileException()
    {
        $this->expectException(Kito\Lab\Storage\FileSystem\File\Exception::class);

        throw new Kito\Lab\Storage\FileSystem\File\Exception('Test');
    }

    public function testKitoLabStringException()
    {
        $this->expectException(Kito\Lab\String\Exception::class);

        throw new Kito\Lab\String\Exception('Test');
    }

    public function testKitoLabSystemException()
    {
        $this->expectException(Kito\Lab\System\Exception::class);

        throw new Kito\Lab\System\Exception('Test');
    }

    public function testKitoLibraryNotFoundException()
    {
        $this->expectException(Kito\LibraryNotFoundException::class);

        throw new Kito\LibraryNotFoundException('Test');
    }

    public function testKitoLoaderException()
    {
        $this->expectException(Kito\Loader\Exception::class);

        throw new Kito\Loader\Exception('Test');
    }

    public function testKitoMathException()
    {
        $this->expectException(Kito\Math\Exception::class);

        throw new Kito\Math\Exception('Test');
    }

    public function testKitoMinifierException()
    {
        $this->expectException(Kito\Minifier\Exception::class);

        throw new Kito\Minifier\Exception('Test');
    }

    public function testKitoNotImplementedException()
    {
        $this->expectException(Kito\NotImplementedException::class);

        throw new Kito\NotImplementedException('Test');
    }

    public function testKitoRouterException()
    {
        $this->expectException(Kito\Router\Exception::class);

        throw new Kito\Router\Exception('Test');
    }

    public function testKitoSQLCommandException()
    {
        $this->expectException(Kito\SQL\CommandException::class);

        throw new Kito\SQL\CommandException('Test');
    }

    public function testKitoSQLConnectException()
    {
        $this->expectException(Kito\SQL\ConnectException::class);

        throw new Kito\SQL\ConnectException('Test');
    }

    public function testKitoSQLConnectionClosedException()
    {
        $this->expectException(Kito\SQL\ConnectionClosedException::class);

        throw new Kito\SQL\ConnectionClosedException('Test');
    }

    public function testKitoSQLCountException()
    {
        $this->expectException(Kito\SQL\CountException::class);

        throw new Kito\SQL\CountException('Test');
    }

    public function testKitoSQLDeleteException()
    {
        $this->expectException(Kito\SQL\DeleteException::class);

        throw new Kito\SQL\DeleteException('Test');
    }

    public function testKitoSQLException()
    {
        $this->expectException(Kito\SQL\Exception::class);

        throw new Kito\SQL\Exception('Test');
    }

    public function testKitoSQLGetResultSetException()
    {
        $this->expectException(Kito\SQL\GetResultSetException::class);

        throw new Kito\SQL\GetResultSetException('Test');
    }

    public function testKitoSQLInsertException()
    {
        $this->expectException(Kito\SQL\InsertException::class);

        throw new Kito\SQL\InsertException('Test');
    }

    public function testKitoSQLMaxException()
    {
        $this->expectException(Kito\SQL\MaxException::class);

        throw new Kito\SQL\MaxException('Test');
    }

    public function testKitoSQLMinException()
    {
        $this->expectException(Kito\SQL\MinException::class);

        throw new Kito\SQL\MinException('Test');
    }

    public function testKitoSQLQueryException()
    {
        $this->expectException(Kito\SQL\QueryException::class);

        throw new Kito\SQL\QueryException('Test');
    }

    public function testKitoSQLSelectDBException()
    {
        $this->expectException(Kito\SQL\SelectDBException::class);

        throw new Kito\SQL\SelectDBException('Test');
    }

    public function testKitoSQLSelectException()
    {
        $this->expectException(Kito\SQL\SelectException::class);

        throw new Kito\SQL\SelectException('Test');
    }

    public function testKitoSQLTooManyRowsException()
    {
        $this->expectException(Kito\SQL\TooManyRowsException::class);

        throw new Kito\SQL\TooManyRowsException('Test');
    }

    public function testKitoSQLUpdateException()
    {
        $this->expectException(Kito\SQL\UpdateException::class);

        throw new Kito\SQL\UpdateException('Test');
    }

    public function testKitoTypeException()
    {
        $this->expectException(Kito\Type\Exception::class);

        throw new Kito\Type\Exception('Test');
    }
}
